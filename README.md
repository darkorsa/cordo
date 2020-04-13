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

It's compliant with PSRs: `PSR-1`, `PSR-2`, `PSR-3`, `PSR-4`, `PSR-7`, `PSR-11`, `PSR-15`, `PSR-18`.

Main goal to create this framework was to have an efficient tool to build API based applications with good architectural foundations and as little "magic" and configuration as possible.

**Note:** Cordo is still in development. Some of the basic features are implemented but tests are still missing. Please keep that in mind.

## Requirements

- PHP 7.4.0 or newer
- Apache/Nginx
- MySql 5.7
- PHP Redis extension (default driver for queues)

## Install

If you'd like to utilize of the *Queues* functionality make sure that you have Redis server installed on your machine by typing in your shell:

``` bash
$ redis-cli ping
```
If Redis is properly installed it replies with *PONG*. Otherwise you should install it from [PECL](https://pecl.php.net/) repository:

``` bash
$ sudo pecl install redis
$ sudo service php7.4-fpm restart
```

Next clone this repository into your new project folder.

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
- All the neccessary DB tables for OAuth2
- Uuid DB helper functions

After that you are good to go with Authentication, Acl and basic CRUD operations in Backoffice\Users module.

## Still missing
- Caching

## How things work

Cordo does not reinvent the wheel. It is basically a set of popular PHP libraries put together and configured in order to create a simple framework that is in compliance with good programming practices for modern PHP.

Some of the used libraries:

- [Doctrine](https://www.doctrine-project.org/)
- [OAuth2](https://bshaffer.github.io/oauth2-server-php-docs/)
- [Fast Route](https://github.com/nikic/FastRoute)
- [Guzzle](http://docs.guzzlephp.org/en/stable/)
- [Tactician](https://tactician.thephpleague.com/)
- [Fractal](https://fractal.thephpleague.com/)
- [Plates](http://platesphp.com/)
- [Monolog](https://github.com/Seldaek/monolog)
- [PHP-DI](http://php-di.org/)
- [Bernard](https://bernard.readthedocs.io/)
- [Whoops](http://filp.github.io/whoops/)
- [Relay](https://relayphp.com/)
- [Symfony Console](https://symfony.com/doc/current/components/console.html)
- [Symfony Dotenv](https://symfony.com/doc/current/components/dotenv.html)
- [Laminas ACL](https://docs.laminas.dev/laminas-permissions-acl/)
- [Laminas Mail](https://docs.laminas.dev/laminas-mail/)

This documentation does not focus on describing in detail how to deal with Routes, Command Bus, DI Container, querying DB, etc., for that use the documentation of the relevant library.

You are also encouraged to find for yourself how things work under the hood, check the [Cordo Core](https://github.com/darkorsa/cordo-core) library where abstract classes and interfaces are located.

If you want to see how the code can be organizen within all the layers, how to utilize of `CQRS`, `Repository Pattern`, `Events`, `Queues`, etc. take a look at the [Backoffice Bundle](https://github.com/darkorsa/cordo-bundle-backoffice). Check the installation instructions [here](https://github.com/darkorsa/cordo-bundle-backoffice/blob/master/README.md).

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

Feature commands should be registered in `app/[Context]/[PackageName]/UI/Console/commands.php` file.

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
    'Backoffice\Users',
    // add you packages here
];
```

Once you package is registered, framework will have access to defined routes, DI container definitions, configs, commands, etc.

### Package structure

Users package is shipped by default with implemented basic CRUD actions.

Here's how the code is organised:

``` bash
app/Backoffice/Users/
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
│   │   └── Register
│   │       └── UsersListeners.php
│   ├── Query
│   │   ├── UserFilter.php
│   │   ├── UserQuery.php
│   │   └── UserView.php
│   ├── Service
│   │   └── UserQueryService.php
│   ├── config
│   │   └── users.php
│   ├── definitions.php
│   └── handlers.php
├── Domain
│   ├── Event
│   │   └── UserCreated.php
│   ├── User.php
│   ├── UserActive.php
│   ├── UserEmail.php
│   ├── UserId.php
│   ├── UserPassword.php
│   ├── UserPasswordHash.php
│   └── UserRepository.php
├── Infrastructure
│   └── Persistance
│       └── Doctrine
│           ├── ORM
│           │   ├── Metadata
│           │   │   └── App.Backoffice.Users.Domain.User.dcm.xml
│           │   └── UserDoctrineRepository.php
│           └── Query
│               ├── UserDoctrineFilter.php
│               └── UserDoctrineQuery.php
├── UI
│   ├── Console
│   │   ├── Command
│   │   │   └── CreateNewUserConsoleCommand.php
│   │   └── commands.php
│   ├── Http
│   │   ├── Auth
│   │   │   └── OAuth2UserCredentials.php
│   │   ├── Controller
│   │   │   ├── UserCommandsController.php
│   │   │   └── UserQueriesController.php
│   │   ├── Middleware
│   │   │   └── OAuthMiddleware.php
│   │   └── Route
│   │       ├── OAuthRoutes.php
│   │       └── UsersRoutes.php
│   ├── Transformer
│   │   └── UserTransformer.php
│   ├── Validator
│   │   ├── EmailExistsValidation.php
│   │   ├── NewUserValidator.php
│   │   └── UpdateUserValidator.php
│   ├── trans
│   │   ├── mail.en.yaml
│   │   ├── mail.es.yaml
│   │   └── mail.pl.yaml
│   └── views
│       └── mail
│           └── new-user-welcome.php
└── UsersInit.php
```

This structure consists of layers: **User Interface**, **Application**, **Domain** and **Infrastructure**.

**Important!**

If you want to quickly boilerplate your new module there's a command for that:

``` bash
php cordo system:module-builder <context> <module_name> [module_archive_file]
```

you can find pre-prepared archive in `app/resources/module`.

That can save you a lot of time as this will generate the whole structure of folders and files for typycial CRUD.

### Routes

Route definitons should be located at `app/[Context]/[PackageName]/UI/Http/Route/[PackageName]Routes.php` file. Routes loader class should inherit from abstract class `Cordo\Core\Application\Service\Register\RoutesRegister`.

Routing is done with use of [FastRoute](https://github.com/nikic/FastRoute) but modified allowing to use per route `Middlewares`.

Perferable way to generate API documentation is [ApiDoc](http://apidocjs.com) but that can be changed according to individual preferences.

### Dependency Injection Container

DI Conteriner definitions should be placed in `app/[Context]/[PackageName]/Application/definitions.php`.

Cordo uses [PHP-ID](http://php-di.org/) for DI Container, if you need to find out more check the [documentation](http://php-di.org/doc/).

### Config

Global config files should be located at *config/* dir, while feature configs location should be: `app/[Context]/[PackageName]/Application/config/`

Config files should return PHP associative arrays. Multidimensional arrays are supproted.

Usage:
``` php
$limit = $config->get('users.limit');
```
where "users" is the name of the config file and the following segments are array associative keys.

### Database

Database configuration is located at `bootstrap/db.php` file. Framework uses [Doctrine](https://www.doctrine-project.org/) for database storage and object mapping.

According to the CQRS approach preferable way is to use [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html) for storing and [Doctrine DBAL](https://www.doctrine-project.org/projects/dbal.html) for querying.

Doctine is preconfigured to support [UUID](https://github.com/ramsey/uuid-doctrine).

[XML Mapping](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/xml-mapping.html) also is supported so you can map your Domain Models directly with database tables. You should place your mappings in `app/[Context]/[PackageName]/Infractructure/Doctrine/ORM/Metadata/`.

When you have your mappings ready you can create/update/drop schema directly from composer:

``` php
composer schema-create
composer schema-update
composer schema-drop
```

### Mailer

For mail [Laminas Mail](https://docs.laminas.dev/laminas-mail/) is used. Currently there are 2 drivers for mail transport `log` (for development) and `smtp`. You can define your mail driver and credentials in `config/mail.php` file.

In order to add/change drivers you can just replace `MailerFactory` class with your own in `bootstrap/app.php`.

### Internationalization ###

Translations are handled with [Symfony Translations](https://symfony.com/doc/current/translation.html). Default file format for translations is `Yaml`. 

You should place your translation files in `app/[Context]/[PackageName]/UI/trans/` folder or subfolders. Naming convention is: `[domain].[locale].yaml`, for example: `mail.en.yaml`.

Usage:
``` php
$translator->trans('hello', [], 'mail', 'en')
```

You can set per request locale by adding `&lang` parameter to request uri or by adding `--lang` flag while running console command.

Config file for translations is located at `config/trans.php`.

### Views ###

[Plates](http://platesphp.com/) is used as a template engine. Place your view files in `app/[Context]/[PackageName]/UI/views/` folder or subfolders.

Usage:
``` php
$templates->render('[context].[module]::[subfolder]/[template_name]', [
    // data you want to pass to the template file
]);
```

### Command bus

Cordo uses [Tactician](https://tactician.thephpleague.com/) command bus for implementing `Command Pattern`.

Your Command -> Handler mappings should be placed in: `app/[Context]/[PackageName]/Application/handlers.php` file.

Command bus is configured to lock each handler in seperate transaction, it also supports events, queues, command logging. Check `bootstrap/command_bus.php` and [Tactician](https://tactician.thephpleague.com/) documentation for details.

### Events

In contrast to the Command -> Handler mapping where for one *Command* there can be one and only one *Handler* you can have several listeners for a single emmited event.

Your listeners definitions should be located at: `app/[Context]/[PackageName]/Event/Loader/[PackageName]Listeners.php`. Events loaders class should extend `Cordo\Core\Application\Service\Register\ListenersRegister`.

Here is how you can emit an event:

``` php
/**
 * @var League\Event\EmitterInterface
 */
$emitter->emit('users.created', new UserCreated($command->email()));
```

Define your listeners in `app/[Context]/[PackageName]/Application/events.php` file (see example in Users module):

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

If you want to make your Command to be queued just make it implementing `Cordo\Core\Application\Queue\MessageInterface` interface or just extend `Cordo\Core\Application\Queue\AbstractMessage` class.

To launch background process that will process queued commands run in the console:

``` shell
php queue-worker &
```

To better understand how to deal with events check Users module how welcome message is being sent for newly created users.

### OAuth2

OAuth2 authorization is shipped with *Users* module and is ready to use. Endpoints for token generation and token refresh are located at `app/Backoffice/Users/UI/Http/Route/OAuthRoutes.php`. Default Client ID is *Cordo*.

General OAuth2 configuration is performed during *Users* initialization at: `app/Backoffice/Users/UsersInit.php`. 

If you'd like to change default credentials check behavior you can do it here: `app/Backoffice/Users/UI/Http/Auth/OAuth2UserCredentials.php`.

### ACL

For the purpose of Authorization [Zend ACL](https://docs.zendframework.com/zend-permissions-acl/usage/) has been used. ACL roles, resources, permissions cen be defined seperately in each package in `app/[Context]/[PackageName]/Application/Acl/[PackageName]Acl.php` which should extend `Cordo\Core\Application\Service\Register\AclRegister`.

In `Auth` package that is shipped with this framework there are CRUD actions prepared for users ACL rules.

There is also a `Middeware` for authorizing access to API endpoints in `system/Module/Auth/UI/Http/Middleware/AclMiddleware.php`. You can pass privilage name in constructor or leave empty then it will simply map http request method (GET, POST, PUT, DELETE) as acl privilage.

### Errors

By default all the errors are logged to the `storage/logs/error.log` file.

Additionally in `dev` environment errors will be prompth to the screen in pretty format using [Whoops](https://github.com/filp/whoops). Errors in console are also pretty formated. In `production` environment errors stack traces will be emailed to the addresses defined in `config/error.php`.

If you'd like to change any of that bevavior you can to it in: `bootstrap/error.php` file.

## Credits

- [Dariusz Korsak][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/darkorsa
[link-contributors]: ../../contributors

