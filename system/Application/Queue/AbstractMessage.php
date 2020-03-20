<?php

namespace Cordo\Core\Application\Queue;

class AbstractMessage implements MessageInterface
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
