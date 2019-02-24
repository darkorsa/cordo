<?php

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\ServerRequestInterface;

return [
    'config' => DI\create(Config::class)->constructor(config_path(), DI\get(Parser::class)),
    'lang' => DI\create(Config::class)->constructor(resources_path() . 'lang', DI\get(Parser::class)),
    'request' => DI\get(ServerRequestInterface::class),
    'logger' => DI\get(LoggerInterface::class),
    ServerRequestInterface::class => DI\factory('GuzzleHttp\Psr7\ServerRequest::fromGlobals'),
    LoggerInterface::class => DI\create(Logger::class)
        ->constructor('logger')
        ->method('pushHandler', new StreamHandler(storage_path().'logs/turbo.log', Logger::WARNING)),
    // services
];
