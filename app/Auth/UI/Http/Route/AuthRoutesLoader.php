<?php

declare(strict_types=1);

namespace App\Auth\UI\Http\Route;

use OAuth2\Request;
use System\Application\Service\Loader\BaseRoutesLoader;

class AuthRoutesLoader extends BaseRoutesLoader
{
    public function load(): void
    {
        $this->addOauthToken();
        $this->addOauthTokenRefresh();
        $this->addAclRoutes();
    }

    private function addOauthToken(): void
    {
        $this->router->addRoute(
            'POST',
            '/token',
            function () {
                $request = Request::createFromGlobals();

                $response = $this->container->get('oauth_server')->handleTokenRequest($request);

                if ($response->getStatusText() === 'OK') {
                    $response->setParameter('login', $request->request('username'));
                }

                $response->send();
                die;
            }
        );
    }

    private function addOauthTokenRefresh(): void
    {
        $this->router->addRoute(
            'POST',
            '/token-refresh',
            function () {
                $response = $this->container->get('oauth_server')->handleTokenRequest(Request::createFromGlobals());
                $response->send();
                die;
            }
        );
    }

    private function addAclRoutes(): void
    {
        $aclRoutesLoader = new AclRoutesLoader($this->router, $this->container, $this->resource);
        $aclRoutesLoader->load();
    }
}
