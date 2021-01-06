<?php

return [
    /**
     * Default queue name
     *
     * @var string
     */
    'default_queue' => 'default',

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    'tries' => 5,

    'driver' => env('QUEUE_DRIVER', 'file'),

    'drivers' => [
        'redis' => [
            'server' => env('REDIS_SERVER'),
            'port' => env('REDIS_PORT'),
            'secret' => env('REDIS_SECRET'),
            'prefix' => env('REDIS_OPT_PREFIX', 'bernard:')
        ],
        'file' => [
            'path' => storage_path() . 'messages/'
        ]
    ]
];
