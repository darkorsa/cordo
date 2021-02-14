<?php

return [
    'driver' => 'mysql',

    'drivers' => [
        'mysql' => [
            'database' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
        ],
    ],
];
