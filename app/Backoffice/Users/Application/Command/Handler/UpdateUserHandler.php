<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command\Handler;

use App\Backoffice\Users\Domain\User;
use League\Event\EmitterInterface;
use App\Backoffice\Users\Domain\UserRepository;
use App\Backoffice\Users\Application\Command\UpdateUser;

class UpdateUserHandler
{
    private $users;

    private $emitter;

    public function __construct(UserRepository $users, EmitterInterface $emitter)
    {
        $this->users = $users;
        $this->emitter = $emitter;
    }

    public function __invoke(UpdateUser $command): void
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
