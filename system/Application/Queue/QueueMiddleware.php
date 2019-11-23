<?php

namespace System\Application\Queue;

use Bernard\Producer;
use League\Tactician\Middleware;
use System\Application\Queue\AbstractMessage;

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
        if ($command instanceof AbstractMessage) {
            $command->fire();
            $this->producer->produce($command, $this->queue);
            return;
        }

        return $next($command);
    }
}
