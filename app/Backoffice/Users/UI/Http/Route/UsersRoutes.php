<?php

declare(strict_types=1);

namespace App\Backoffice\Users\UI\Http\Route;

use App\Backoffice\Shared\UI\Http\Middleware\AclMiddleware;
use Cordo\Core\Application\Service\Register\RoutesRegister;
use App\Backoffice\Shared\UI\Http\Middleware\AuthMiddleware;

class UsersRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->addGetUsers();
        $this->addGetUser();
        $this->addCreateUser();
        $this->addUpdateUser();
        $this->addDeleteUser();
    }

    private function addGetUsers(): void
    {
        /**
         * @api {get} /backoffice-users Get users list
         * @apiName GetUsers
         * @apiGroup BackofficeUsers
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
        $this->router->addRoute(
            'GET',
            "/backoffice-users",
            'App\Backoffice\Users\UI\Http\Controller\UserQueriesController@index',
            [new AclMiddleware($this->container)]
        );
    }

    private function addGetUser(): void
    {
        /**
         * @api {get} /backoffice-users/:id Get user
         * @apiName GetUser
         * @apiGroup BackofficeUsers
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
        $this->router->addRoute(
            'GET',
            "/backoffice-users/" . static::UUID_PATTERN,
            'App\Backoffice\Users\UI\Http\Controller\UserQueriesController@get',
            [new AclMiddleware($this->container)]
        );
    }

    private function addCreateUser(): void
    {
        /**
         * @api {post} /backoffice-users Create new user
         * @apiName CreateUser
         * @apiGroup BackofficeUsers
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
        $this->router->addRoute(
            'POST',
            "/backoffice-users",
            'App\Backoffice\Users\UI\Http\Controller\UserCommandsController@create',
            [new AclMiddleware($this->container)]
        );
    }

    private function addUpdateUser()
    {
        /**
         * @api {put} /backoffice-users Update user
         * @apiName UpdateUser
         * @apiGroup BackofficeUsers
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
        $this->router->addRoute(
            'PUT',
            "/backoffice-users",
            'App\Backoffice\Users\UI\Http\Controller\UserCommandsController@update',
            [new AuthMiddleware($this->container), new AclMiddleware($this->container)]
        );
    }

    private function addDeleteUser(): void
    {
        /**
         * @api {delete} /backoffice-users/:id Delete user
         * @apiName DeleteUser
         * @apiGroup BackofficeUsers
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
        $this->router->addRoute(
            'DELETE',
            "/backoffice-users",
            'App\Backoffice\Users\UI\Http\Controller\UserCommandsController@delete',
            [new AuthMiddleware($this->container), new AclMiddleware($this->container)]
        );
    }
}
