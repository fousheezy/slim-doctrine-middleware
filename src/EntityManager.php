<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Jgut\Slim\Doctrine;

use Interop\Container\ContainerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Mapping\Driver\YamlDriver;

/**
 * Doctrine service.
 */
class EntityManager
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public static function get(array $options = [])
    {
        $proxyDir = self::getOption($options, 'proxy_path');
        $cache = self::getOption($options, 'cache_driver');

        $config = Setup::createConfiguration(false, $proxyDir, $cache);
        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        self::setupAnnotationMetadata($options);

        if (!self::setupMetadataDriver($config, $options)) {
            throw new \RuntimeException('No Metadata Driver defined');
        }

        $connection = self::getOption($options, 'connection');

        return DoctrineEntityManager::create($connection, $config);
    }

    /**
     * Get option value or default if none existent
     *
     * @param array $options
     * @param string $option
     * @param mixed $default
     *
     * @return mixed
     */
    protected static function getOption($options, $option, $default = null)
    {
        return isset($options[$option]) ? $options[$option] : $default;
    }

    /**
     * Set up annotation metadata
     *
     * @param array $options
     */
    protected static function setupAnnotationMetadata(array $options = [])
    {
        $annotationFiles = self::getOption($options, 'annotation_files');
        if ($annotationFiles) {
            foreach ($annotationFiles as $file) {
                AnnotationRegistry::registerFile($file);
            }
        }

        $annotationNamespaces = self::getOption($options, 'annotation_namespaces');
        if ($annotationNamespaces) {
            AnnotationRegistry::registerAutoloadNamespaces($annotationNamespaces);
        }

        $annotationAuloaders = self::getOption($options, 'annotation_autoloaders');
        if ($annotationAuloaders) {
            foreach ($annotationAuloaders as $autoloader) {
                AnnotationRegistry::registerLoader($autoloader);
            }
        }
    }

    /**
     * Set up annotation metadata
     *
     * @param \Doctrine\ORM\Configuration $config
     * @param array $options
     *
     * @return bool
     */
    protected static function setupMetadataDriver(Configuration &$config, array $options = [])
    {
        $annotationPaths = self::getOption($options, 'annotation_paths');
        if ($annotationPaths) {
            $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($annotationPaths, false));
        }

        $xmlPaths = self::getOption($options, 'xml_paths');
        if ($xmlPaths) {
            $config->setMetadataDriverImpl(new XmlDriver($xmlPaths));
        }

        $yamlPaths = self::getOption($options, 'yaml_paths');
        if ($yamlPaths) {
            $config->setMetadataDriverImpl(new YamlDriver($yamlPaths));
        }

        return $annotationPaths || $xmlPaths || $yamlPaths;
    }
}
