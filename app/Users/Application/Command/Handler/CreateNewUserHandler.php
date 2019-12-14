<?php

declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use App\Users\Domain\User;
use League\Event\EmitterInterface;
use App\Users\Domain\UserRepository;
use App\Users\Domain\Event\UserCreated;
use App\Users\Application\Command\CreateNewUser;
use System\Module\Auth\Application\Service\AuthServiceInterface;

class CreateNewUserHandler
{
    private $authService;

    private $users;

    private $emitter;

    public function __construct(AuthServiceInterface $authService, UserRepository $users, EmitterInterface $emitter)
    {
        $this->authService = $authService;
        $this->users = $users;
        $this->emitter = $emitter;
    }

    public function handle(CreateNewUser $command): void
    {
        User::assertPasswordIsValid($command->password());

        $password = $this->authService->hashPassword($command->password());

        $user = new User(
            $command->id(),
            $command->email(),
            $password,
            $command->isActive(),
            $command->createdAt(),
            $command->updatedAt()
        );

        $this->users->add($user);

        $this->emitter->emit('users.created', new UserCreated($command->email()));
    }
}
