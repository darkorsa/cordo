<?php

namespace System\Application\Handler;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

class HandleInflector implements MethodNameInflector
{
    public function inflect($command, $commandHandler)
    {
        return 'handle';
    }
}
