<?php

use OAuth2\Request;

$router->addRoute(
    'POST',
    '/token',
    static function () use ($container) {
        $request = Request::createFromGlobals();

        $response = $container->get('oauth_server')->handleTokenRequest($request);

        if ($response->getStatusText() === 'OK') {
            $response->setParameter('login', $request->request('username'));
        }

        $response->send();
        die;
    }
);

$router->addRoute(
    'POST',
    '/token-refresh',
    static function () use ($container) {
        $response = $container->get('oauth_server')->handleTokenRequest(Request::createFromGlobals());

        $response->send();
        die;
    }
);
