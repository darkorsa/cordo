<?php

namespace App\Backoffice\Users;

use App\Backoffice\Users\SharedKernel\Consts\OAuthConsts;
use DI\Container;
use OAuth2\Scope;
use OAuth2\Server;
use OAuth2\Storage\Pdo;
use OAuth2\Storage\Memory;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\ClientCredentials;
use Cordo\Core\Application\Service\Register\ModuleInit;
use App\Backoffice\Users\UI\Http\Auth\OAuth2UserCredentials;

class UsersInit implements ModuleInit
{
    public static function init(Container $container, bool $isRunningInConsole): void
    {
        if ($isRunningInConsole) {
            return;
        }

        self::initOAuthServer($container);
    }

    private static function initOAuthServer(Container $container): void
    {
        $config = $container->get('config');

        // setting storage
        $storage = new Pdo([
            'dsn' => 'mysql:dbname=' . env('DB_DATABASE') . ';host=' . env('DB_HOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ]);

        // custom credentials
        $credentials = $container->get(OAuth2UserCredentials::class);

        // setting scope
        $scopeUtil = new Scope(new Memory(array(
            'default_scope' => OAuthConsts::OAUTH_SCOPE,
            'supported_scopes' => OAuthConsts::OAUTH_ALLOWED_SCOPES
        )));

        $server = new Server($storage, [
            'access_lifetime' => $config->get('auth.expire'),
        ]);
        $server->addGrantType(new ClientCredentials($storage));
        $server->addGrantType(new UserCredentials($credentials));
        $server->addGrantType(new RefreshToken($storage, [
            'always_issue_new_refresh_token' => $config->get('auth.always_issue_new_refresh_token'),
        ]));
        $server->setScopeUtil($scopeUtil);

        $container->set('backoffice_oauth_server', $server);
    }
}
