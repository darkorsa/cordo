<?php declare(strict_types=1);

namespace App\Users\Application\Command;

use App\Users\Application\Event\UserCreated;
use App\Users\Application\Command\CreateNewUser;
use System\Application\Command\Handler\AbstractHandler;

class CreateNewUserHandler extends AbstractHandler
{
    public function handle(CreateNewUser $command): void
    {
        echo 'dodano!' . PHP_EOL;
        $this->emitter->emit('users.created', new UserCreated($command->email()));
    }
}
