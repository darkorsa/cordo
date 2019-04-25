<?php

namespace App\Users\Infrastructure\Persistance\Doctrine\ORM;

use App\Users\Domain\User;
use Doctrine\ORM\EntityManager;
use App\Users\Domain\UsersInterface;

class UserRepository implements UsersInterface
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
}
