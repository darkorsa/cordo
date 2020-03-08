<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Infrastructure\Persistance\Doctrine\ORM;

use App\Backoffice\Users\Domain\User;
use Doctrine\ORM\EntityManager;
use App\Backoffice\Users\Domain\UserRepository;
use System\Application\Exception\ResourceNotFoundException;

class UserDoctrineRepository implements UserRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $id): User
    {
        /**
         * @var \App\Backoffice\Users\Domain\User | null $user
         */
        $user = $this->entityManager->find(User::class, $id);

        if (!$user) {
            throw new ResourceNotFoundException();
        }

        return $user;
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(User $user): void
    {
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
