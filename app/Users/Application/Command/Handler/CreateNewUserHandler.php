<?php declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use App\Users\Domain\User;
use League\Event\EmitterInterface;
use App\Users\Domain\UsersInterface;
use App\Users\Application\Event\UserCreated;
use App\Users\Application\Command\CreateNewUser;

class CreateNewUserHandler
{
    private $users;

    private $emitter;
    
    public function __construct(UsersInterface $users, EmitterInterface $emitter)
    {
        $this->users = $users;
        $this->emitter = $emitter;
    }
    
    public function handle(CreateNewUser $command): void
    {
        $user = new User(
            $command->id(),
            $command->email(),
            $command->password(),
            $command->isActive(),
            $command->createdAt()
        );

        $this->users->add($user);

        $this->emitter->emit('users.created', new UserCreated($command->email()));
    }
}