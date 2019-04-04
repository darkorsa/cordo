<?php

namespace System\Application\Queue;

use League\Tactician\Bernard\QueueableCommand;

class AbstractMessage implements QueueableCommand
{
    public function getName()
    {
        return get_class($this);
    }
}
