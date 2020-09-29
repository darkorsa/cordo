<?php

return [
    'channels' => [
        'log' => [
            'path' => storage_path() . 'logs/error.log',
        ],
        'mail' => [
            'error_reporting_emails' => explode(',', env('ERROR_REPORTING_EMAILS')),
        ],
        'rollbar' => [
            'access_token' => env('ROLLBAR_TOKEN'),
            'environment' => env('APP_ENV'),
            'root' => root_path(),
        ],
    ],
    'stacks' => [
        'dev' => [
            'log',
        ],
        'production' => [
            'log',
        ],
    ],
];
