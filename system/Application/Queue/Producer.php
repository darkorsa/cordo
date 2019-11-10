<?php

namespace System\Application\Queue;

use Bernard\Util;
use Bernard\Message;
use Bernard\Envelope;
use Bernard\QueueFactory;
use Bernard\BernardEvents;
use Bernard\Event\EnvelopeEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Producer
{
    protected $queues;
    protected $dispatcher;

    /**
     * @param QueueFactory             $queues
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(QueueFactory $queues, EventDispatcherInterface $dispatcher)
    {
        $this->queues = $queues;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Message     $message
     * @param string|null $queueName
     */
    public function produce(Message $message, $queueName = null)
    {
        $queueName = $queueName ?: Util::guessQueue($message);

        $queue = $this->queues->create($queueName);
        $queue->enqueue($envelope = new Envelope($message));

        $this->dispatcher->dispatch(new EnvelopeEvent($envelope, $queue), BernardEvents::PRODUCE);
    }
}
