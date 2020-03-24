<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Event\Register;

use App\Backoffice\Users\Domain\Event\UserCreated;
use Cordo\Core\Application\Service\Register\ListenersRegister;
use App\Backoffice\Users\Application\Event\Listener\UserCreatedListener;

class UsersListeners extends ListenersRegister
{
    public function register(): void
    {
        $this->emitter->addListener(
            "backoffice.users.created",
            function (UserCreated $userCreated) {
                (new UserCreatedListener($this->container))->handle($userCreated);
            }
        );
    }
}
