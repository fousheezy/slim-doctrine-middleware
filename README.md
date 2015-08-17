[![Latest Version](https://img.shields.io/packagist/vpre/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-doctrine-middleware)
[![License](https://img.shields.io/packagist/l/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://github.com/juliangut/slim-doctrine-middleware/blob/master/LICENSE)

[![Build status](https://img.shields.io/travis/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://travis-ci.org/juliangut/slim-doctrine-middleware)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware)
[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-doctrine-middleware)

# Juliangut Slim Framework Doctrine handler middleware

Doctrine handler middleware for Slim Framework.

## Installation

Best way to install is using [Composer](https://getcomposer.org/):

```
php composer.phar require juliangut/slim-doctrine-middleware
```

Then require_once the autoload file:

```php
require_once './vendor/autoload.php';
```

## Usage

Just add as any other middleware.

```php
// Create Slim app
$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Entity Manager in the container
$container['entity_manager'] = function () {
    return new \Jgut\Slim\Doctrine\EntitytManager;
};

// Add routes
$app->get('/', function () {
    $this->entityManager->beginTransaction();
    // Do your stuff
    $this->entityManager->commit();
});

$app->run();
```

### Configuration

```php
// Minimun configuration
$config = [
    'connection' => [
        'driver' => 'pdo_sqlite',
        'memory' => true,
    ],
    'annotation_paths' => ['path_to_entities_files'],
];

// Create Slim app
$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Entity Manager in the container
$container['entityManager'] = function () use ($config) {
    return new \Jgut\Slim\Doctrine\EntitytManager($config);
};
```

### Available configurations

* `connection` array of PDO configurations or \Doctrine\DBAL\Connection
* `cache_driver` \Doctrine\Common\Cache\Cache
* `proxy_path` path were Doctrine creates its proxy classes, defaults to /tmp
* `annotation_files` array of Doctrine annotations files
* `annotation_namespaces` array of Doctrine annotations namespaces
* `annotation_autoloaders` array of Doctrine annotations autoloader callables
* `annotation_paths` array of paths where to find annotated entity files
* `xml_paths` array of paths where to find XML entity mapping files
* `yaml_paths` array of paths where to find YAML entity mapping files

#### Note:

`annotation_paths`, `xml_paths` or `yaml_paths` is needed by Doctrine to include a Metadata Driver

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/slim-doctrine-middleware/issues). Have a look at existing issues before

See file [CONTRIBUTING.md](https://github.com/juliangut/slim-doctrine-middleware/blob/master/CONTRIBUTING.md)

## License

### Release under BSD-3-Clause License.

See file [LICENSE](https://github.com/juliangut/slim-doctrine-middleware/blob/master/LICENSE) included with the source code for a copy of the license terms

