<?php

declare(strict_types=1);

namespace System\Module\Auth\Application\Command\Handler;

use System\Module\Auth\Domain\AclRepository;
use League\Event\EmitterInterface;
use System\Module\Auth\Application\Command\DeleteUserAcl;

class DeleteUserAclHandler
{
    private $acl;

    private $emitter;

    public function __construct(AclRepository $acl, EmitterInterface $emitter)
    {
        $this->acl = $acl;
        $this->emitter = $emitter;
    }

    public function __invoke(DeleteUserAcl $command): void
    {
        $acl = $this->acl->find($command->id());

        $this->acl->delete($acl);
    }
}
