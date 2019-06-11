# cordo

Cordo is a microframework designed to efficienly develop REST APIs based on architecture layered architecture and using principles such as:

- Domain Driven Design
- CQRS (Command Query Responsibility Segregation)
- Event Dispatcher
- Queues (Redis, RabbitMQ, Amazon SQS)
- OAuth2
- Package by feature

Framework is compliant with PSRs: PSR-1, PSR-2, PSR-3, PSR-4, PSR-7, PSR-11, PSR-15, PSR-18

## Requirements

- PHP 7.3 or newer
- Apache/Nginx
- MySql 5.7
- PHP Redis extension (default driver for queues)

## Install

Just clone this repository into your new project folder

``` bash
$ git clone git@github.com:darkorsa/cordos.git .
```

## How things work

### Entry points

There are several entry points to the application:

#### Web

Entry point for HTTP requests is *public/index.php*. Your apache/nginx configuration should point to the *public* folder.

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

Global commands should be registered in *./cordo* file by adding them to the application object:

``` php
$application->add(new SecurityCheckerCommand(new SecurityChecker()));
```

Feature commands should be registered in *app/[packageName]/UI/Console/commands.php* file.

``` php
return [
    YourConsoleCommand::class,
    AnotherConsoleCommand::class
];
```

#### Queues

For background processing [Bernard](https://bernard.readthedocs.io/) is used.

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

This framework is configured with Redis Extention driver by default. Driver declaration is placed in *bootstrap/queue_factory.php* and can be changed there.

If you want to make your Command to be queued just make it implement *League\Tactician\Bernard\QueueableCommand* interface.

To launch background process that will process queued commands run in the console:

``` shell
php queue-worker &
```

### Registering new package

This framework uses package by feature approach. It means that you organize your code in packages placed in *app/* folder.

Just add your package folder name to the *app/Loader.php*:

``` php
protected static $register = [
        'Auth',
        'Users',
        // add you packages here
    ];
```

Once you package is registered, framework will have access to defined routes, DI container definitions, configs, commands, etc.

### Package structure

Framework comes with Users package shipped by default with implemented basic CRUD actions.

Here's how the code is organised:

``` bash
app/Users/
├── Application
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
│   ├── events.php
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
    │   └── routes.php
    ├── Transformer
    │   └── UserTransformer.php
    └── Validator
        └── UserValidator.php
```

This structure represents the *Domain Driven Design* model, which consists of layers: **User Interface**, **Application**, **Domain** and **Infrastructure**.

### Routes

Route definitons should be placed in *app/[packageName]/UI/Http/routes.php* file.

Routing is done with use of [FastRoute](https://github.com/nikic/FastRoute) but modified allowing to use per route *Middlewares*.

Perferable way to generate API documentation is [ApiDoc](http://apidocjs.com) but that can be changed according to individual preferences.

### Dependency Injection Container

DI Conteriner definitions should be placed in *app/[packageName]/Application/definitions.php* file.

Framework uses [PHP-ID](http://php-di.org/) for DI Container, if you need to find out more check the [documentation](http://php-di.org/doc/).

### Config

Global config files should be located at *config/* dir, while feature configs location should be: *app/[packageName]/Application/config/*

Config files should return PHP associative arrays. Multidimensional arrays are supproted.

Usage:
``` php
$limit = $config->get('users.limit');
```
where users is the name of the config file and the following segments are array associative keys.

### Database

### Command bus

### Events