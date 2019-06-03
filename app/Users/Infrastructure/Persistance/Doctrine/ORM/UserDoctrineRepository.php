<?php declare(strict_types=1);

namespace App\Users\Infrastructure\Persistance\Doctrine\ORM;

use App\Users\Domain\User;
use Doctrine\ORM\EntityManager;
use App\Users\Domain\UserRepository;

class UserDoctrineRepository implements UserRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(User $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(User $user) : void
    {
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    public function delete(User $user) : void
    {
        $entity = $this->entityManager->merge($user);

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
