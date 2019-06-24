<?php

use App\Users\Application\Event\UserCreated;
use App\Users\Application\Event\Listener\UserCreatedListener;

$emitter->addListener(
    'users.created',
    static function ($event, UserCreated $userCreated) use ($container) {
        $listener = new UserCreatedListener($container);
        $listener->handle($userCreated);
    }
);
