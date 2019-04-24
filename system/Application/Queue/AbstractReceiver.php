<?php

namespace System\Application\Queue;

use Bernard\Message;
use Bernard\Receiver;

abstract class AbstractReceiver implements Receiver
{
    public function receive(Message $message)
    {
        $this->handle($message);
    }
}
