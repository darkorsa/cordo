<?php

declare(strict_types=1);

namespace App\Users\Application\Event\Register;

use App\Users\Domain\Event\UserCreated;
use System\Application\Service\Register\ListenersRegister;
use App\Users\Application\Event\Listener\UserCreatedListener;

class UsersListeners extends ListenersRegister
{
    public function register(): void
    {
        $this->emitter->addListener(
            "{$this->resource}.created",
            function ($event, UserCreated $userCreated) {
                $listener = new UserCreatedListener($this->container);
                $listener->handle($userCreated);
            }
        );
    }
}
