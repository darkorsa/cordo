<?php

return [
    /**
     * Enable / disable app debug mode
     */
    'debug' => env('APP_DEBUG'),

    /**
     * Application environment: dev | production
     */
    'environment' => env('APP_ENV'),

    'core' => [
        Cordo\Core\Application\Bootstrap\Init\ErrorHandlerInit::class,
        Cordo\Core\Application\Bootstrap\Init\MailerInit::class,
        Cordo\Core\Application\Bootstrap\Init\DatabaseInit::class,
        Cordo\Core\Application\Bootstrap\Init\TranslatorInit::class,
        Cordo\Core\Application\Bootstrap\Init\AclInit::class,
        Cordo\Core\Application\Bootstrap\Init\ValidationInit::class,
    ],

    'modules' => [
        // register your modules here
        App\Welcome\Message\ModuleRegister::class,
    ],

    'oauth' => env('APP_ENABLE_OAUTH', false),
];
