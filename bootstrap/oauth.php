<?php

use OAuth2\Server;
use OAuth2\Storage\Pdo;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\ClientCredentials;

$config = $container->get('config');

$storage = new Pdo([
    'dsn' => 'mysql:dbname=' . getenv('DB_DATABASE') . ';host=' . getenv('DB_HOST'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD')
]);
      
$tokenLifetime = $config->get('auth.expire');

$server = new Server($storage, [
    'access_lifetime' => $tokenLifetime,
]);

$credentialsClass = $config->get('auth.credentials');
$credentials = new $credentialsClass();

$server->addGrantType(new ClientCredentials($storage));
$server->addGrantType(new UserCredentials($credentials));
$server->addGrantType(new RefreshToken($storage, [
    'always_issue_new_refresh_token' => $config->get('auth.always_issue_new_refresh_token')
]));

return $server;
