<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Event\Listener;

use League\Event\EventInterface;
use Cordo\Core\Application\Event\Listener\AbstractListener;
use App\Backoffice\Users\Application\Command\SendUserWelcomeMessage;

class UserCreatedListener extends AbstractListener
{
    /**
     * Handle event
     *
     * @param \App\Backoffice\Users\Domain\Event\UserCreated $event
     * @return void
     */
    public function handle(EventInterface $event): void
    {
        $command = new SendUserWelcomeMessage();
        $command->email = $event->email();
        $command->locale = $this->container->get('translator')->getLocale();

        $this->commandBus->handle($command);
    }
}
