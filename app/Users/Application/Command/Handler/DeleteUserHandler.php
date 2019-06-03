<?php declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use App\Users\Domain\User;
use League\Event\EmitterInterface;
use App\Users\Domain\UserRepository;
use App\Users\Application\Command\DeleteUser;

class DeleteUserHandler
{
    private $users;

    private $emitter;

    public function __construct(UserRepository $users, EmitterInterface $emitter)
    {
        $this->users = $users;
        $this->emitter = $emitter;
    }

    public function handle(DeleteUser $command): void
    {
        $user = new User(
            $command->id(),
            $command->email(),
            $command->password(),
            $command->isActive(),
            $command->createdAt(),
            $command->updatedAt()
        );

        $this->users->delete($user);
    }
}
