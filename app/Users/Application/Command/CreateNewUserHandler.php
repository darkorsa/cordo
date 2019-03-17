<?php declare(strict_types=1);

namespace App\Users\Application\Command;

use System\Application\Handler\Handler;
use App\Users\Application\Event\UserCreated;
use App\Users\Application\Command\CreateNewUser;

class CreateNewUserHandler extends Handler
{
    public function handle(CreateNewUser $command): void
    {
        echo 'dodano!' . PHP_EOL;
        $this->emitter->emit('users.created', new UserCreated($command->email()));
    }
}
