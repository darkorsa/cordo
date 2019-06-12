# cordos

Cordos is a microframework designed to efficienly develop REST APIs based on architecture and principles such as:

- Domain Driven Design
- CQRS (Command Query Responsibility Segregation)
- Event Dispatcher
- Queues (Redis, RabbitMQ, Amazon SQS)
- OAuth2
- Package by feature

Framework is compliant with PSRs: PSR-1, PSR-2, PSR-3, PSR-4, PSR-7, PSR-11, PSR-15, PSR-18

## Install

Just clone this repository into your new project folder

``` bash
$ git clone git@github.com:darkorsa/cordos.git .
```

## How things work

This framework uses package by feature approach. It means that you organize your code in packages placed in *app/* folder.

### Registering new package

Just add your package folder name to the *app/Loader.php* file, just like this:

``` php
protected static $register = [
        'Auth',
        'Users',
        // add you packages here
    ];
```

Once you package is registered framework will have access to defined routes, DI container definitions, configs, commands, etc.

### Package structure

Cordos comes with Users package shipped by default with implemented CRUD.

Here how the code is organised:

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

Route definitons should be placed in *app/[packageName]/UI/Http/routes.php file*.

Routing is done with use of [FastRoute](https://github.com/nikic/FastRoute) but modified allowing to use per route *Middlewares*.

Perferable way to generate API documentation is [ApiDoc](http://apidocjs.com) but that can be changed according to individual preferences.

### Dependency Injection Container

DI Conteriner definitions file should be placed in *app/[packageName]/Application/definitions.php*

Framework uses [PHP-ID](http://php-di.org/) for DI Container, if you need to find out more, the documentation can be found [here](http://php-di.org/doc/).
