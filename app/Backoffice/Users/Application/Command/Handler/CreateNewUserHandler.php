<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command\Handler;

use League\Event\EmitterInterface;
use App\Backoffice\Users\Domain\User;
use App\Backoffice\Users\Domain\UserId;
use App\Backoffice\Users\Domain\UserEmail;
use App\Backoffice\Users\Domain\UserActive;
use App\Backoffice\Users\Domain\UserPassword;
use App\Backoffice\Users\Domain\UserRepository;
use App\Backoffice\Users\Domain\UserPasswordHash;
use App\Backoffice\Users\Application\Command\CreateNewUser;

class CreateNewUserHandler
{
    private $users;

    private $emitter;

    public function __construct(UserRepository $users, EmitterInterface $emitter)
    {
        $this->users = $users;
        $this->emitter = $emitter;
    }

    public function __invoke(CreateNewUser $command): void
    {
        $user = new User(
            UserId::random(),
            new UserEmail($command->email()),
            new UserPasswordHash((new UserPassword($command->password()))->value()),
            new UserActive(true),
            $command->createdAt(),
            $command->createdAt()
        );

        $this->users->add($user);
        $user->created();

        $this->emitter->emitBatch($user->pullDomainEvents());
    }
}
