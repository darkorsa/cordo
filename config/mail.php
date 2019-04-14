<?php

return [
    'driver'    => getenv('MAIL_DRIVER'),
    // smtp
    'host'      => getenv('MAIL_HOST'),
    'port'      => getenv('MAIL_PORT'),
    'username'  => getenv('MAIL_USERNAME'),
    'password'  => getenv('MAIL_PASSWORD'),
    // log
    'log_path'  => storage_path() . 'logs/mail.log',
];
