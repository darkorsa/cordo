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
    ],

    'modules' => [
        // register your modules here
        App\Welcome\Message\ModuleRegister::class,
    ],
];
