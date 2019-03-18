<?php

use App\Loader;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = Loader::loadEntities();
$isDevMode = getenv('APP_ENV');

// the connection configuration
$dbParams = [
    'dbname'    => getenv('DB_DATABASE'),
    'user'      => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'host'      => getenv('DB_HOST'),
    'driver'    => 'pdo_mysql',
];

$config = Setup::createXMLMetadataConfiguration($paths, $isDevMode);

return EntityManager::create($dbParams, $config);
