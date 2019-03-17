<?php

use App\Loader;
use Monolog\Logger;
use League\Tactician\CommandBus;
use Monolog\Handler\StreamHandler;
use League\Tactician\Logger\LoggerMiddleware;
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\CommandEvents\EventMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use System\Application\Command\Handler\HandleInflector;
use League\Tactician\Logger\Formatter\ClassNameFormatter;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;

$commandHandlerMiddleware = new CommandHandlerMiddleware(
    new ClassNameExtractor(),
    new ContainerLocator($container, Loader::loadHandlersMap()),
    new HandleInflector()
);

$emitter = $container->get('emitter');
Loader::loadListeners($emitter, $container);

$commandLogger = new Logger('command');
$commandLogger->pushHandler(new StreamHandler(storage_path().'logs/command.log', Logger::DEBUG));

$commandBus = new CommandBus([
    new LoggerMiddleware(new ClassNameFormatter(), $logger),
    new LockingMiddleware(),
    new EventMiddleware($emitter),
    $commandHandlerMiddleware,
]);

return $commandBus;
