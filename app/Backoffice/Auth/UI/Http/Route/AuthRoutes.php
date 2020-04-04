<?php

declare(strict_types=1);

namespace App\Backoffice\Auth\UI\Http\Route;

use OAuth2\Request;
use Cordo\Core\Application\Service\Register\RoutesRegister;

class AuthRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->addOauthToken();
        $this->addOauthTokenRefresh();
    }

    private function addOauthToken(): void
    {
        /**
         * @api {post} /backoffice-auth/token Generate auth token
         * @apiName AuthToken
         * @apiGroup BackofficeAuth
         *
         * @apiParam {String} [username] Username
         * @apiParam {String} [password] Password
         * @apiParam {String} [grant_type] Grant type (default value 'password')
         * @apiParam {String} [client_id] Client ID - default value is 'Cordo', can be changed in oauth_clients db table
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
            '/backoffice-auth/token',
            function () {
                $request = Request::createFromGlobals();

                $response = $this->container->get('backoffice_oauth_server')->handleTokenRequest($request);

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
        /**
         * @api {post} /backoffice-auth/token-refresh Refresh auth token
         * @apiName RefreshAuthToken
         * @apiGroup BackofficeAuth
         *
         * @apiParam {String} [username] Username
         * @apiParam {String} [password] Password
         * @apiParam {String} [grant_type] Grant type (default value 'password')
         * @apiParam {String} [client_id] Client ID - default value is 'Cordo', can be changed in oauth_clients db table
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
            '/backoffice-auth/token-refresh',
            function () {
                $response = $this->container
                    ->get('backoffice_oauth_server')
                    ->handleTokenRequest(Request::createFromGlobals());
                $response->send();
                die;
            }
        );
    }
}
