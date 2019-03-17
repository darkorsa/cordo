<?php declare(strict_types=1);

namespace App\Users\Application\Event\Listener;

use League\Event\EventInterface;
use System\Application\Event\AbstractListener;
use App\Users\Application\Command\SendUserWelcomeMessage;

class UserCreatedListener extends AbstractListener
{
    public function handle(EventInterface $event): void
    {
        $command = new SendUserWelcomeMessage($event->email());

        $this->commandBus->handle($command);
    }
}
