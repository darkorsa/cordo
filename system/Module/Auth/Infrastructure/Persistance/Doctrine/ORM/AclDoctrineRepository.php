<?php

declare(strict_types=1);

namespace System\Module\Auth\Infrastructure\Persistance\Doctrine\ORM;

use System\Module\Auth\Domain\Acl;
use Doctrine\ORM\EntityManager;
use System\Module\Auth\Domain\AclRepository;
use System\Application\Exception\ResourceNotFoundException;

class AclDoctrineRepository implements AclRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $id): Acl
    {
        $result = $this->entityManager->find(Acl::class, $id);

        if (!$result) {
            throw new ResourceNotFoundException();
        }

        return $result;
    }

    public function add(Acl $acl): void
    {
        $this->entityManager->persist($acl);
        $this->entityManager->flush();
    }

    public function update(Acl $acl): void
    {
        $this->entityManager->merge($acl);
        $this->entityManager->flush();
    }

    public function delete(Acl $acl): void
    {
        $this->entityManager->remove($acl);
        $this->entityManager->flush();
    }
}
