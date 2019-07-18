<?php declare(strict_types=1);

namespace App\Auth\Infrastructure\Persistance\Doctrine\ORM;

use App\Auth\Domain\Acl;
use Doctrine\ORM\EntityManager;
use App\Auth\Domain\AclRepository;

class AclDoctrineRepository implements AclRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $entity = $this->entityManager->merge($acl);

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
