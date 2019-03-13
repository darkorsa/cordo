<?php

use OAuth2\Server;
use OAuth2\Storage\Pdo;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\ClientCredentials;

$storage = new Pdo([
    'dsn' => 'mysql:dbname=' . getenv('DB_DATABASE') . ';host=' . getenv('DB_HOST'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD')
]);
      
$tokenLifetime = $container->get('config')->get('auth.expire');

$server = new Server($storage, [
    'access_lifetime' => $tokenLifetime,
]);

$userCredentials = new \System\Auth\UserCredentials(); // replace with custom implementation

$server->addGrantType(new ClientCredentials($storage));
$server->addGrantType(new UserCredentials($userCredentials));
$server->addGrantType(new RefreshToken($storage, [
    'always_issue_new_refresh_token' => true
]));

return $server;
