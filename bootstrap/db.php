<?php

use App\Loader;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = Loader::loadEntities();
$isDevMode = getenv('APP_ENV') == 'local';

// the connection configuration
$dbParams = [
    'dbname'    => getenv('DB_DATABASE'),
    'user'      => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'host'      => getenv('DB_HOST'),
    'driver'    => 'pdo_mysql',
];

Type::addType('uuid_binary_ordered_time', 'Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType');

$config = Setup::createXMLMetadataConfiguration($paths, $isDevMode);

$em = EntityManager::create($dbParams, $config);
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('uuid_binary_ordered_time', 'binary');

return $em;
