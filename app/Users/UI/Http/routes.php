<?php

use App\Auth\UI\Http\Middleware\OAuth;

$router->addRoute(
    'GET',
    '/users',
    'App\Users\UI\Http\Controller\UserQueriesController@index'
);

$router->addRoute(
    'GET',
    '/users/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}',
    'App\Users\UI\Http\Controller\UserQueriesController@get'
);

$router->addRoute(
    'PUT',
    '/users',
    'App\Users\UI\Http\Controller\UserCommandsController@create'
);

$router->addRoute(
    'POST',
    '/users',
    'App\Users\UI\Http\Controller\UserCommandsController@update',
    [new OAuth($container)]
);

/**
 * @api {delete} /users/:id Delete user
 * @apiName DeleteUser
 * @apiGroup Users
 *
 * @apiParam {Number} id User ID
 * @apiParam {String} access_token Auth token
 *
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 404 Not Found
 *
 * @apiSuccessExample Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "response": "OK"
 * }
 */
$router->addRoute(
    'DELETE',
    '/users',
    'App\Users\UI\Http\Controller\UserCommandsController@delete',
    [new OAuth($container)]
);

