<?php declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use App\Users\Domain\User;
use League\Event\EmitterInterface;
use App\Users\Domain\UsersInterface;
use App\Users\Application\Command\UpdateUser;

class UpdateUserHandler
{
    private $users;

    private $emitter;
    
    public function __construct(UsersInterface $users, EmitterInterface $emitter)
    {
        $this->users = $users;
        $this->emitter = $emitter;
    }
    
    public function handle(UpdateUser $command): void
    {
        $user = new User(
            $command->id(),
            $command->email(),
            $command->password(),
            $command->isActive(),
            $command->createdAt(),
            $command->updatedAt()
        );

        $this->users->update($user);
    }
}
