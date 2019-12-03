<?php

namespace System\Application\Queue;

use League\Tactician\Bernard\QueueableCommand;

interface MessageInterface extends QueueableCommand
{
    public function getName();

    public function fire(): void;

    public function fired(): int;
}
