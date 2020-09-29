<?php

return [
    'driver' => env('MAIL_DRIVER'),
    'drivers' => [
        'smtp' => [
            'host' => env('MAIL_SMTP_HOST'),
            'port' => env('MAIL_SMTP_PORT'),
            'username' => env('MAIL_SMTP_USERNAME'),
            'password' => env('MAIL_SMTP_PASSWORD'),
        ],
        'log' => [
            'path' => storage_path() . 'logs/mail.log',
        ],
    ],
    'from' => env('MAIL_FROM'),
];
