<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

$dotenv = new Dotenv();
$dotenv->load(root_path().'.env');

$dbParams = [
    'dbname'    => getenv('DB_DATABASE'),
    'user'      => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'host'      => getenv('DB_HOST'),
    'driver'    => 'pdo_mysql',
];

$connection = DriverManager::getConnection($dbParams);

return new HelperSet([
    'db' => new ConnectionHelper($connection),
]);
