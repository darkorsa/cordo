<?php

namespace App\Users\Infrastructure\Doctrine;

use App\Users\Domain\User;
use Doctrine\ORM\EntityManager;
use App\Users\Domain\UsersInterface;

class DoctrineUsers implements UsersInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(User $user) : void
    {
        echo 'zapisaneee!';
        
        //$this->entityManager->persist($user);
        //$this->entityManager->flush();
    }
}
