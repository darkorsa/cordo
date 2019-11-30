<?php

declare(strict_types=1);

namespace App\Auth\Application\Command\Handler;

use App\Auth\Domain\AclRepository;
use League\Event\EmitterInterface;
use App\Auth\Application\Command\DeleteUserAcl;

class DeleteUserAclHandler
{
    private $acl;

    private $emitter;

    public function __construct(AclRepository $acl, EmitterInterface $emitter)
    {
        $this->acl = $acl;
        $this->emitter = $emitter;
    }

    public function handle(DeleteUserAcl $command): void
    {
        $acl = $this->acl->find($command->id());

        $this->acl->delete($acl);
    }
}
