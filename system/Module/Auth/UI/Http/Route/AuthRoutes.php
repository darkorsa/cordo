<?php

declare(strict_types=1);

namespace System\Module\Auth\UI\Http\Route;

use OAuth2\Request;
use System\Application\Service\Register\RoutesRegister;

class AuthRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->addOauthToken();
        $this->addOauthTokenRefresh();
        $this->addAclRoutes();
    }

    private function addOauthToken(): void
    {
        /**
         * @api {post} /token Authorize user
         * @apiName AuthToken
         * @apiGroup Auth
         *
         * @apiParam {String} [username] Username
         * @apiParam {String} [password] Password
         * @apiParam {String} [grant_type] Grant type (should have value 'password')
         * @apiParam {String} [client_id] Client ID - default value 'Cordo', can ba changed in oauth_clients db table
         *
         * @apiSuccessExample Success-Response:
         * HTTP/1.1 200 OK
         * {
         *   "access_token": "f5dee6f234b6ac0333958643f6728736f812513a",
         *   "expires_in": 3600,
         *   "token_type": "Bearer",
         *   "scope": null,
         *   "refresh_token": "6edc70d399c9594c693429554ae9067d49735419",
         *   "login": "user@email.com"
         * }
         */
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
        $aclRoutesLoader = new AclRoutes($this->router, $this->container, $this->resource);
        $aclRoutesLoader->register();
    }
}
