# cordo

Cordo is a microframework designed to efficienly develop REST APIs based on layered architecture and using principles such as:

- DDD (Domain Driven Design)
- CQRS (Command Query Responsibility Segregation)
- Event Dispatcher
- Queues (Redis, RabbitMQ, Amazon SQS)
- OAuth2
- Package by feature
- Zero config approach

Cordo is compliant with PSRs: PSR-1, PSR-2, PSR-3, PSR-4, PSR-7, PSR-11, PSR-15, PSR-18

**Note:** Cordo is still in development. All the basic features are implemented but tests are still missing. Please keep that in mind.

## Requirements

- PHP 7.3 or newer
- Apache/Nginx
- MySql 5.7
- PHP Redis extension (default driver for queues)

## Install

Just clone this repository into your new project folder.

``` bash
$ git clone git@github.com:darkorsa/cordos.git .
```

If you would like to utilize of UUIDs you might find usefull to create some db functions helping translating UUIDs between strigs and binaries.

``` bash
composer sql-import resources/database/sql/uuid.sql
```

If you plan to use OAuth2 authorization running:

``` bash
composer sql-import resources/database/sql/oauth.sql
```

will help you creating all the neccessary db tables.

## How things work

Cordo does not reinvent the wheel. It is basically a set of popular PHP libraries brought together and configured in order to create a simple framework that is in compliance good programming practices for modern PHP.

Some of the used libraries:
- Doctrine
- Fast Route
- Guzzle
- Tactician
- Fractal
- Monolog
- PHP-DI
- Bernard
- Whoops
- Relay
- Symfony Console
- Sumfony Dotenv
- Zend Mail

Cordo is shipped with one previously prepared module: *Users*. It presents how the code should be organized within all the layers and utilizes of *Events* and *Queues*.

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

Feature commands should be registered in *app/[PackageName]/UI/Console/commands.php* file.

``` php
return [
    YourConsoleCommand::class,
    AnotherConsoleCommand::class
];
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

This framework is configured with Redis Extention driver by default. Driver declaration is placed in *bootstrap/queue_factory.php* and can be changed there.

If you want to make your Command to be queued just make it implementing *League\Tactician\Bernard\QueueableCommand* interface.

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

Users package is shipped by default with implemented basic CRUD actions.

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

Route definitons should be placed in *app/[PackageName]/UI/Http/routes.php* file.

Routing is done with use of [FastRoute](https://github.com/nikic/FastRoute) but modified allowing to use per route *Middlewares*.

Perferable way to generate API documentation is [ApiDoc](http://apidocjs.com) but that can be changed according to individual preferences.

### Dependency Injection Container

DI Conteriner definitions should be placed in *app/[PackageName]/Application/definitions.php* file.

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

Database configuration is located at *bootstrap/db.php* file. Framework uses [Doctrine](https://www.doctrine-project.org/) for database storage and object mapping.

According to the CQRS approach preferable way is to use [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html) for storing and [Doctrine DBAL](https://www.doctrine-project.org/projects/dbal.html) for querying.

Doctine is preconfigured to support [UUID](https://github.com/ramsey/uuid-doctrine).

Also [XML Mapping](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/xml-mapping.html) is supported so you can map your Domain Models directly with database tables. You should place your mappings in *app/[PackageName]/Infractructure/Doctrine/ORM/Metadata/*.

### Command bus

Cordo uses [Tactician](https://tactician.thephpleague.com/) command bus for implementing command pattern.

Your Command -> Handler mappings should be placed in: *app/[PackageName]/Application/handlers.php* file.

Command bus is configured to lock each handler in seperate transaction, it also supports events, queues, command logging. Check *bootstrap/command_bus.php* and [Tactician](https://tactician.thephpleague.com/) documentation for details.

### Events

In contrast to the Command -> Handler mapping where for one *Command* there can be one and only one *Handler* you can have several listeners for a single emmited event.

Here is how you can emit an event:

``` php
/**
 * @var League\Event\EmitterInterface
 */
$emitter->emit('users.created', new UserCreated($command->email()));
```

Define your listeners in *app/[PackageName]/Application/events.php* file just like in Users module:

``` php
$emitter->addListener(
    'users.created',
    static function ($event, UserCreated $userCreated) use ($container) {
        $listener = new UserCreatedListener($container);
        $listener->handle($userCreated);
    }
);
```

To better understand how to deal with events check Users module how welcome message is being sent for newly created users.

### Errors

By default all errors are logged to the *storage/logs/error.log* file.

Additionally in **dev** environment errors will be prompth to the screen in pretty format using [Whoops](https://github.com/filp/whoops). Errors in console are also pretty formated. In **production** environment errors stack traces will be emailed to the addresses defined in *config/error.php*.

If you'd like to change any of that bevavior you can to it in: *bootstrap/error.php* file.


