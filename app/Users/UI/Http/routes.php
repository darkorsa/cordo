<?php

use App\Auth\UI\Http\Middleware\OAuth;

/**
 * @api {get} /users Get users list
 * @apiName GetUsers
 * @apiGroup Users
 *
 * @apiParam {Number} [offset] Records offset
 * @apiParam {Number} [limit] Records limit
 *
 * @apiSuccessExample Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "response": {
 *         "data": [
 *             {
 *                 "type": "user",
 *                 "id": "8c8d6582-851a-11e9-92e2-3035ada7b0f2",
 *                 "attributes": {
 *                     "email": "admin@test.pl",
 *                     "modified": {
 *                         "date": "2019-06-02 09:41:08.000000",
 *                         "timezone_type": 3,
 *                         "timezone": "UTC"
 *                     }
 *                 },
 *                 "links": {
 *                     "self": "http://plain.ninja/user/8c8d6582-851a-11e9-92e2-3035ada7b0f2"
 *                 }
 *             }
 *         ],
 *         "total": 1
 *     }
 * }
 */
$router->addRoute(
    'GET',
    '/users',
    'App\Users\UI\Http\Controller\UserQueriesController@index'
);

/**
 * @api {get} /users/:id Get user
 * @apiName GetUser
 * @apiGroup Users
 *
 * @apiParam {Number} id User ID
 *
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 404 Not Found
 *
 * @apiSuccessExample Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     "response": {
 *         "data": {
 *             "type": "user",
 *             "id": "8c8d6582-851a-11e9-92e2-3035ada7b0f2",
 *             "attributes": {
 *                 "email": "admin@test.pl",
 *                 "modified": {
 *                     "date": "2019-06-02 09:41:08.000000",
 *                     "timezone_type": 3,
 *                     "timezone": "UTC"
 *                 }
 *             },
 *             "links": {
 *                 "self": "http://plain.ninja/user/8c8d6582-851a-11e9-92e2-3035ada7b0f2"
 *             }
 *         }
 *     }
 * }
 */
$router->addRoute(
    'GET',
    '/users/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}',
    'App\Users\UI\Http\Controller\UserQueriesController@get'
);

/**
 * @api {put} /users Create new user
 * @apiName CreateUser
 * @apiGroup Users
 *
 * @apiParam {String{..50}} email E-mail address
 * @apiParam {String{6..18}} password User password
 *
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 400 Bad Request
 * {
 *     "response": {
 *         "password": {
 *             "LengthBetween::TOO_SHORT": "password must be 6 characters or longer"
 *         },
 *         "email": {
 *             "Email::INVALID_VALUE": "email must be a valid email address"
 *         }
 *     }
 * }
 *
 * @apiSuccessExample Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "response": "OK"
 * }
 */
$router->addRoute(
    'PUT',
    '/users',
    'App\Users\UI\Http\Controller\UserCommandsController@create'
);

/**
 * @api {post} /users Update user
 * @apiName UpdateUser
 * @apiGroup Users
 *
 * @apiParam {String{..50}} email E-mail address
 * @apiParam {String} access_token Auth token
 *
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 401 Unauthorized
 *
 * @apiErrorExample {json} Error-Response:
 * HTTP/1.1 400 Bad Request
 * {
 *     "response": {
 *         "email": {
 *             "Email::INVALID_VALUE": "email must be a valid email address"
 *         }
 *     }
 * }
 *
 * @apiSuccessExample Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "response": "OK"
 * }
 */
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
 * HTTP/1.1 401 Unauthorized
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
