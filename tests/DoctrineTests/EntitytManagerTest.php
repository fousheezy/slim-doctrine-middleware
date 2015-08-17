<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Jgut\Slim\DoctrineTests;

use Jgut\Slim\Doctrine\EntityManager;

/**
 * @covers Jgut\Slim\Doctrine\EntityManager
 */
class EntityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Slim\Doctrine\EntityManager::setupAnnotationMetadata
     * @expectedException \RuntimeException
     */
    public function testAnnotationsNoDriver()
    {
        $options = [
            'annotation_files' => [dirname(__DIR__) . '/files/fakeAnnotationFile.php'],
            'annotation_namespaces' => ['\Jgut\Slim\Doctrine'],
            'annotation_autoloaders' => [function () {
            }],
        ];

        EntityManager::get($options);
    }

    /**
     * @cover \Jgut\Slim\Doctrine\EntityManager::setupMetadataDriver
     * @expectedException \InvalidArgumentException
     */
    public function testDriversNoConnection()
    {
        $options = [
            'annotation_paths' => sys_get_temp_dir(),
            'xml_paths' => [dirname(__DIR__) . '/files/fakeAnnotationFile.php'],
            'yaml_paths' => [dirname(__DIR__) . '/files/fakeAnnotationFile.php'],
        ];

        EntityManager::get($options);
    }

    /**
     * @cover \Jgut\Slim\Doctrine\EntityManager::setupAnnotationMetadata
     * @cover \Jgut\Slim\Doctrine\EntityManager::setupMetadataDriver
     */
    public function testCreation()
    {
        $options = [
            'connection' => [
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ],
            'annotation_paths' => sys_get_temp_dir(),
        ];

        $this->assertInstanceOf('\Doctrine\ORM\EntityManager', EntityManager::get($options));
    }
}
