<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command\Handler;

use League\Event\EmitterInterface;
use App\Backoffice\Users\Domain\User;
use App\Backoffice\Users\Domain\UserEmail;
use App\Backoffice\Users\Domain\UserActive;
use App\Backoffice\Users\Domain\UserPasswordHash;
use App\Backoffice\Users\Domain\UserRepository;
use App\Backoffice\Users\Application\Command\UpdateUser;
use App\Backoffice\Users\Domain\UserId;

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
        $user = $this->users->find($command->id());
        
        $user = new User(
            new UserId($command->id()),
            new UserEmail($command->email()),
            new UserPasswordHash($user->password()),
            new UserActive($command->isActive()),
            $user->createdAt(),
            $command->updatedAt()
        );

        $this->users->update($user);
    }
}
