<?php

namespace App\Backoffice\Users;

use DI\Container;
use OAuth2\Server;
use OAuth2\Storage\Pdo;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\ClientCredentials;
use App\Backoffice\Users\UI\Http\Auth\OAuth2UserCredentials;
use Cordo\Core\Application\Service\Register\ModuleInit;

class UsersInit implements ModuleInit
{
    public static function init(Container $container, bool $isRunningInConsole): void
    {
        if (!$isRunningInConsole) {
            self::initOAuthServer($container);
        }
    }

    private static function initOAuthServer(Container $container): void
    {
        $config = $container->get('config');

        $storage = new Pdo([
            'dsn' => 'mysql:dbname=' . env('DB_DATABASE') . ';host=' . env('DB_HOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ]);

        $tokenLifetime = $config->get('auth.expire');

        $server = new Server($storage, [
            'access_lifetime' => $tokenLifetime,
        ]);

        $credentials = $container->get(OAuth2UserCredentials::class);

        $server->addGrantType(new ClientCredentials($storage));
        $server->addGrantType(new UserCredentials($credentials));
        $server->addGrantType(new RefreshToken($storage, [
            'always_issue_new_refresh_token' => $config->get('auth.always_issue_new_refresh_token'),
        ]));

        $container->set('oauth_server', $server);
    }
}
