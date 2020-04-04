<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Application\Command\Handler;

use App\Backoffice\Acl\Domain\Acl;
use App\Backoffice\Acl\Domain\AclRepository;
use League\Event\EmitterInterface;
use App\Backoffice\Users\Domain\UserRepository;
use App\Backoffice\Acl\Application\Command\CreateUserAcl;

class CreateUserAclHandler
{
    private $acl;

    private $users;

    private $emitter;

    public function __construct(AclRepository $acl, UserRepository $users, EmitterInterface $emitter)
    {
        $this->acl = $acl;
        $this->users = $users;
        $this->emitter = $emitter;
    }

    public function __invoke(CreateUserAcl $command): void
    {
        $user = $this->users->find($command->userId());

        $acl = new Acl(
            $command->id(),
            $user,
            $command->privileges(),
            $command->createdAt(),
            $command->updatedAt()
        );

        $this->acl->add($acl);
    }
}
