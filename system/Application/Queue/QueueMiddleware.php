<?php

namespace System\Application\Queue;

use Bernard\Message;
use Bernard\Producer;
use League\Tactician\Middleware;
use League\Tactician\Bernard\QueueCommand;
use League\Tactician\Bernard\QueuedCommand;

/**
 * Sends the command to a remote location using message queues
 */
final class QueueMiddleware implements Middleware
{
    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var string
     */
    private $queue;

    /**
     * @param Producer $producer
     */
    public function __construct(Producer $producer, $queue = 'default')
    {
        $this->producer = $producer;
        $this->queue = $queue;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof Message) {
            $this->producer->produce($command, $this->queue);

            return;
        }

        if ($command instanceof QueuedCommand) {
            $command = $command->getCommand();
        }

        if ($command instanceof QueueCommand) {
            $command = $command->getCommand();
        }

        return $next($command);
    }
}
