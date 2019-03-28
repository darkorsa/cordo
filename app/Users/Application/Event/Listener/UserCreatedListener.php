<?php declare(strict_types=1);

namespace App\Users\Application\Event\Listener;

use App\Users\Application\Event\UserCreated;
use System\Application\Event\Listener\AbstractListener;
use App\Users\Application\Command\SendUserWelcomeMessage;

class UserCreatedListener extends AbstractListener
{
    public function handle(UserCreated $event): void
    {
        $command = new SendUserWelcomeMessage($event->email());

        $this->commandBus->handle($command);
    }
}
