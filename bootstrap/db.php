<?php

use App\Register;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Cordo\Core\SharedKernel\Enum\Env;

$paths = Register::registerEntities();
$isDevMode = env('APP_ENV') == Env::DEV();

// the connection configuration
$dbConfig = $container->get('db_config');
$dbParams = [
    'dbname'    => $dbConfig['database'],
    'user'      => $dbConfig['user'],
    'password'  => $dbConfig['password'],
    'host'      => $dbConfig['host'],
    'driver'    => 'pdo_mysql',
];

if (!Type::hasType('uuid_binary_ordered_time')) {
    Type::addType('uuid_binary_ordered_time', 'Cordo\Core\SharedKernel\Uuid\Doctrine\UuidBinaryOrderedTimeType');
}

$proxyDir = storage_path() . 'cache/doctrine/';

$config = Setup::createXMLMetadataConfiguration($paths, $isDevMode, $proxyDir);
$config->setAutoGenerateProxyClasses($isDevMode);

$entityManager = EntityManager::create($dbParams, $config);
$entityManager
    ->getConnection()
    ->getDatabasePlatform()
    ->registerDoctrineTypeMapping('uuid_binary_ordered_time', 'binary');

return $entityManager;
