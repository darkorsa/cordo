<?php

namespace System\Application\Queue;

use League\Tactician\Bernard\QueueableCommand;

class AbstractMessage implements QueueableCommand
{
    public $fired = 0;

    public function getName()
    {
        return get_class($this);
    }

    public function fire(): void
    {
        $this->fired++;
    }

    public function fired(): int
    {
        return $this->fired;
    }
}
