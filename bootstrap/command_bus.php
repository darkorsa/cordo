<?php

use App\Register;
use Monolog\Logger;
use Bernard\Producer;
use League\Tactician\CommandBus;
use Monolog\Handler\StreamHandler;
use League\Tactician\Logger\LoggerMiddleware;
use Cordo\Core\Application\Queue\QueueMiddleware;
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\CommandEvents\EventMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use Symfony\Component\EventDispatcher\EventDispatcher;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Logger\Formatter\ClassNameFormatter;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;

$commandHandlerMiddleware = new CommandHandlerMiddleware(
    new ClassNameExtractor(),
    new ContainerLocator($container, Register::registerHandlersMap()),
    new InvokeInflector()
);

$emitter = $container->get('emitter');
Register::registerListeners($emitter, $container);

$commandLogger = new Logger('command');
$commandLogger->pushHandler(new StreamHandler(storage_path() . 'logs/command.log', Logger::DEBUG));

// Queues
$queueFactory = require 'queue_factory.php';
$producer = new Producer($queueFactory, new EventDispatcher());

return new CommandBus([
    new LoggerMiddleware(new ClassNameFormatter(), $commandLogger),
    new LockingMiddleware(),
    new TransactionMiddleware($entityManager),
    new QueueMiddleware($producer, $container->get('config')->get('queue.default_queue')),
    new EventMiddleware($emitter),
    $commandHandlerMiddleware,
]);
