<?php

return [
    'driver' => env('DB_CONNECTION', 'mysql'),

    'drivers' => [
        'mysql' => [
            'dbname' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
            'driver' => 'pdo_mysql',
        ],
    ],
];
