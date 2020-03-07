<?php

declare(strict_types=1);

namespace System\Module\Auth\Application\Command\Handler;

use System\Module\Auth\Domain\Acl;
use System\Module\Auth\Domain\AclRepository;
use League\Event\EmitterInterface;
use App\Users\Domain\UserRepository;
use System\Module\Auth\Application\Command\UpdateUserAcl;

class UpdateUserAclHandler
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

    public function __invoke(UpdateUserAcl $command): void
    {
        $user = $this->users->find($command->userId());

        $acl = new Acl(
            $command->id(),
            $user,
            $command->privileges(),
            $command->createdAt(),
            $command->updatedAt()
        );

        $this->acl->update($acl);
    }
}
