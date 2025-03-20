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
            'port' => env('DB_PORT', 3306),
            'charset'  => 'utf8mb4',
        ],
        'pgsql' => [
            'dbname' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
            'driver' => 'pdo_pgsql',
            'port' => env('DB_PORT', 5432),
            'charset' => 'utf8',
        ],
    ],
];
