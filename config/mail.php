<?php

return [
    'driver'    => env('MAIL_DRIVER'),
    // smtp
    'host'      => env('MAIL_HOST'),
    'port'      => env('MAIL_PORT'),
    'username'  => env('MAIL_USERNAME'),
    'password'  => env('MAIL_PASSWORD'),
    'from'      => 'noreply@yourdomain.com',
    // log
    'log_path'  => storage_path() . 'logs/mail.log',
];
