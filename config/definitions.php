<?php

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\ServerRequestInterface;

return [
    ServerRequestInterface::class => DI\factory('GuzzleHttp\Psr7\ServerRequest::fromGlobals'),
    LoggerInterface::class => DI\create('Monolog\Logger')
        ->constructor('logger')
        ->method('pushHandler', new StreamHandler(storage_path().'logs/turbo.log', Logger::WARNING)),
    // services
];
