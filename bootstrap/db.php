<?php

use App\Register;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Cordo\Core\SharedKernel\Enum\Env;

$paths = Register::registerEntities();
$isDevMode = getenv('APP_ENV') == Env::DEV();

// the connection configuration
$dbParams = [
    'dbname'    => getenv('DB_DATABASE'),
    'user'      => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'host'      => getenv('DB_HOST'),
    'driver'    => 'pdo_mysql',
];

Type::addType('uuid_binary_ordered_time', 'Cordo\Core\SharedKernel\Uuid\Doctrine\UuidBinaryOrderedTimeType');

$proxyDir = storage_path() . 'cache/doctrine/';

$config = Setup::createXMLMetadataConfiguration($paths, $isDevMode, $proxyDir);
$config->setAutoGenerateProxyClasses($isDevMode);

$entityManager = EntityManager::create($dbParams, $config);
$entityManager
    ->getConnection()
    ->getDatabasePlatform()
    ->registerDoctrineTypeMapping('uuid_binary_ordered_time', 'binary');

return $entityManager;
