<?php declare(strict_types=1);

namespace App\Auth\Application\Command\Handler;

use App\Auth\Domain\Acl;
use App\Auth\Domain\AclRepository;
use League\Event\EmitterInterface;
use App\Users\Domain\UserRepository;
use App\Auth\Application\Command\DeleteUserAcl;

class DeleteUserAclHandler
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

    public function handle(DeleteUserAcl $command): void
    {
        $user = $this->users->find($command->userId());

        $acl = new Acl(
            $command->id(),
            $user,
            $command->privileges(),
            $command->createdAt(),
            $command->updatedAt()
        );

        $this->acl->delete($acl);
    }
}
