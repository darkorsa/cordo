# cordo

Cordo is a microframework designed for efficient development of REST APIs using principles such as:

- Layered architecture
- CQRS (Command Query Responsibility Segregation)
- Event Dispatcher
- Repository pattern
- Queues (Redis, RabbitMQ, Amazon SQS)
- OAuth2
- UUIDs
- Package by feature
- Minimal config approach

It's compliant with PSRs: `PSR-1`, `PSR-2`, `PSR-3`, `PSR-4`, `PSR-7`, `PSR-11`, `PSR-15`, `PSR-18`

**Note:** Cordo is still in development. Some of the basic features are implemented but tests are still missing. Please keep that in mind.

## Requirements

- PHP 7.2 or newer
- Apache/Nginx
- MySql 5.7
- PHP Redis extension (default driver for queues)

## Install

Just clone this repository into your new project folder.

``` bash
$ git clone git@github.com:darkorsa/cordo.git .
```

Run:
``` bash
$ composer install
```

Next copy `.env_example` file and rename it to `.env`. Then complete it with your configuration data.

Final step is running console command:

``` bash
$ php cordo system:init
```

It will create:
- DB schema based on Doctrine XML metadata files
- All the neccessary DB tables for OAuth2
- Uuid DB helper functions

After that you are good to go with Authentication, Acl and basic CRUD operations in Users module.

## Still missing
- Internationalization
- Lightweight HTML template engine
- Cache

## How things work

Cordo does not reinvent the wheel. It is basically a set of popular PHP libraries put together and configured in order to create a simple framework that is in compliance with good programming practices for modern PHP.

Some of the used libraries:

- [Doctrine](https://www.doctrine-project.org/)
- [OAuth2](https://bshaffer.github.io/oauth2-server-php-docs/)
- [Fast Route](https://github.com/nikic/FastRoute)
- [Guzzle](http://docs.guzzlephp.org/en/stable/)
- [Tactician](https://tactician.thephpleague.com/)
- [Fractal](https://fractal.thephpleague.com/)
- [Monolog](https://github.com/Seldaek/monolog)
- [PHP-DI](http://php-di.org/)
- [Bernard](https://bernard.readthedocs.io/)
- [Whoops](http://filp.github.io/whoops/)
- [Relay](https://relayphp.com/)
- [Symfony Console](https://symfony.com/doc/current/components/console.html)
- [Sumfony Dotenv](https://symfony.com/doc/current/components/dotenv.html)
- [Zend ACL](https://docs.zendframework.com/zend-permissions-acl/usage/)
- [Zend Mail](https://framework.zend.com/manual/2.1/en/modules/zend.mail.introduction.html)

This documentation does not focus on describing in detail how to deal with Routes, Command Bus, DI Container, querying DB, etc., for that use the documentation of the relevant library.

You are also encouraged to find for yourself how things work under the hood, check the `system` folder where abstract classes and interfaces are located.

Cordo is shipped with one previously prepared module: `Users`. It presents how the code can be organized within all the layers and utilizes of `Events` and `Queues`.

### Entry points

Entry points to the application:

#### Web

Entry point for HTTP requests is `public/index.php`. Your apache/nginx configuration should point to the `public` folder.

#### Console

Command-line commands are handled with use of [Symfony Console](https://symfony.com/doc/current/components/console.html) component.

You can fire your commands through command-line with:
``` shell
php cordo [command_name]
```

List currently registered commands:
``` shell
php cordo list
```

Global commands should be registered in `./cordo` file by adding them to the application object:

``` php
$application->add(new SecurityCheckerCommand(new SecurityChecker()));
```

Feature commands should be registered in `app/[PackageName]/UI/Console/commands.php` file.

``` php
return [
    YourConsoleCommand::class,
    AnotherConsoleCommand::class
];
```

### Registering new package

This framework uses package by feature approach. It means that you organize your code in packages placed in `app/` folder.

Just add your package folder name to the `app/Register.php`:

``` php
protected static $register = [
    'Auth',
    'Users',
    // add you packages here
];
```

Once you package is registered, framework will have access to defined routes, DI container definitions, configs, commands, etc.

### Package structure

Users package is shipped by default with implemented basic CRUD actions.

Here's how the code is organised:

``` bash
app/Users/
.
├── Application
│   ├── Acl
│   │   └── UsersAcl.php
│   ├── Command
│   │   ├── CreateNewUser.php
│   │   ├── DeleteUser.php
│   │   ├── Handler
│   │   │   ├── CreateNewUserHandler.php
│   │   │   ├── DeleteUserHandler.php
│   │   │   ├── SendUserWelcomeMessageHandler.php
│   │   │   └── UpdateUserHandler.php
│   │   ├── SendUserWelcomeMessage.php
│   │   └── UpdateUser.php
│   ├── Event
│   │   ├── Listener
│   │   │   └── UserCreatedListener.php
│   │   ├── Register
│   │   │   └── UsersListeners.php
│   │   └── UserCreated.php
│   ├── Query
│   │   ├── UserFilter.php
│   │   ├── UserQuery.php
│   │   └── UserView.php
│   ├── Service
│   │   └── UserService.php
│   ├── config
│   │   └── users.php
│   ├── definitions.php
│   └── handlers.php
├── Domain
│   ├── User.php
│   └── UserRepository.php
├── Infrastructure
│   └── Persistance
│       └── Doctrine
│           ├── ORM
│           │   ├── Metadata
│           │   │   └── App.Users.Domain.User.dcm.xml
│           │   └── UserDoctrineRepository.php
│           └── Query
│               ├── UserDoctrineFilter.php
│               └── UserDoctrineQuery.php
└── UI
    ├── Console
    │   ├── Command
    │   │   └── CreateNewUserConsoleCommand.php
    │   └── commands.php
    ├── Http
    │   ├── Auth
    │   │   └── OAuth2UserCredentials.php
    │   ├── Controller
    │   │   ├── UserCommandsController.php
    │   │   └── UserQueriesController.php
    │   └── Route
    │       └── UsersRoutes.php
    ├── Transformer
    │   └── UserTransformer.php
    └── Validator
        └── UserValidator.php
```

This structure consists of layers: **User Interface**, **Application**, **Domain** and **Infrastructure**.

If you want to quickly boilerplate your new module there's a command for that:

``` bash
php cordo system:module-builder <module_name> <module_archive_file>
```

you can find pre-prepared archive in `app/resources/module` folder with typical module structure for CRUD operations. Of course you create and use your own boilerplates.

### Routes

Route definitons should be located at `app/[PackageName]/UI/Http/Route/[PackageName]Routes.php` file. Routes loader class should inherit from abstract class `System\Application\Service\Register\RoutesRegister`.

Routing is done with use of [FastRoute](https://github.com/nikic/FastRoute) but modified allowing to use per route `Middlewares`.

Perferable way to generate API documentation is [ApiDoc](http://apidocjs.com) but that can be changed according to individual preferences.

### Dependency Injection Container

DI Conteriner definitions should be placed in `app/[PackageName]/Application/definitions.php`.

Cordo uses [PHP-ID](http://php-di.org/) for DI Container, if you need to find out more check the [documentation](http://php-di.org/doc/).

### Config

Global config files should be located at *config/* dir, while feature configs location should be: `app/[packageName]/Application/config/`

Config files should return PHP associative arrays. Multidimensional arrays are supproted.

Usage:
``` php
$limit = $config->get('users.limit');
```
where users is the name of the config file and the following segments are array associative keys.

### Database

Database configuration is located at `bootstrap/db.php` file. Framework uses [Doctrine](https://www.doctrine-project.org/) for database storage and object mapping.

According to the CQRS approach preferable way is to use [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html) for storing and [Doctrine DBAL](https://www.doctrine-project.org/projects/dbal.html) for querying.

Doctine is preconfigured to support [UUID](https://github.com/ramsey/uuid-doctrine).

[XML Mapping](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/xml-mapping.html) also is supported so you can map your Domain Models directly with database tables. You should place your mappings in `app/[PackageName]/Infractructure/Doctrine/ORM/Metadata/`.

When you have your mappings ready you can create/update/drop schema directly from composer:

``` php
composer schema-create
composer schema-update
composer schema-drop
```

### Command bus

Cordo uses [Tactician](https://tactician.thephpleague.com/) command bus for implementing `Command Pattern`.

Your Command -> Handler mappings should be placed in: `app/[PackageName]/Application/handlers.php` file.

Command bus is configured to lock each handler in seperate transaction, it also supports events, queues, command logging. Check `bootstrap/command_bus.php` and [Tactician](https://tactician.thephpleague.com/) documentation for details.

### Events

In contrast to the Command -> Handler mapping where for one *Command* there can be one and only one *Handler* you can have several listeners for a single emmited event.

Your listeners definitions should be located at: `app/[PackageName]/Event/Loader/[PackageName]Listeners.php`. Events loaders class should extend `System\Application\Service\Register\ListenersRegister`.

Here is how you can emit an event:

``` php
/**
 * @var League\Event\EmitterInterface
 */
$emitter->emit('users.created', new UserCreated($command->email()));
```

Define your listeners in `app/[PackageName]/Application/events.php` file (see example in Users module):

``` php
$emitter->addListener(
    'users.created',
    static function ($event, UserCreated $userCreated) use ($container) {
        $listener = new UserCreatedListener($container);
        $listener->handle($userCreated);
    }
);
```

#### Queues

For background processing [Bernard](https://bernard.readthedocs.io/) library is used.

Bernard supports several different drivers:

- Google AppEngine
- Doctrine DBAL
- Flatfile
- IronMQ
- MongoDB
- Pheanstalk
- PhpAmqp / RabbitMQ
- Redis Extension
- Predis
- Amazon SQS
- Queue Interop

This framework is configured with Redis Extention driver by default. Driver declaration is placed in `bootstrap/queue_factory.php` and can be changed there.

If you want to make your Command to be queued just make it implementing `League\Tactician\Bernard\QueueableCommand` interface or just extend `System\Application\Queue\AbstractMessage` class.

To launch background process that will process queued commands run in the console:

``` shell
php queue-worker &
```

To better understand how to deal with events check Users module how welcome message is being sent for newly created users.

### OAuth2

OAuth2 authorization method is ready to use. Endpoints for token generation and token refresh are located at `app/Auth/UI/Http/Route/AuthRoutes.php`. Default Client ID is *Cordo*.

General OAuth2 configuration is performed in bootstrap file: `bootstrap/oauth.php`. 

If you'd like change default credentials check behavior you can do it here: `app/Users/UI/Http/Auth/OAuth2UserCredentials.php`.

### ACL

For the purpose of Authorization [Zend ACL](https://docs.zendframework.com/zend-permissions-acl/usage/) has been used. ACL roles, resources, permissions cen be defined seperately in each package in `app/[PackageName]/Application/Acl/[PackageName]Acl.php` which should extend `System\Application\Service\Register\AclRegister`.

In `Auth` package that is shipped with this framework there are CRUD actions prepared for users ACL rules.

There is also a `Middeware` for authorizing access to API endpoints in `app/Auth/UI/Http/Middleware/AclMiddleware.php`. You can pass privilage name in constructor or leave empty then it will simply map http request method (GET, POST, PUT, DELETE) as acl privilage.

### Errors

By default all the errors are logged to the `storage/logs/error.log` file.

Additionally in `dev` environment errors will be prompth to the screen in pretty format using [Whoops](https://github.com/filp/whoops). Errors in console are also pretty formated. In `production` environment errors stack traces will be emailed to the addresses defined in `config/error.php`.

If you'd like to change any of that bevavior you can to it in: `bootstrap/error.php` file.

## Credits

- [Dariusz Korsak][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


