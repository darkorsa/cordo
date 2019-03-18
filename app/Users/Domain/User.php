<?php declare(strict_types=1);

namespace App\Users\Domain;

use Assert\Assert;
use Ramsey\Uuid\Uuid;

final class User
{
    private $id;

    private $email;

    private $password;

    public function __construct(string $email, string $password)
    {
        Assert::that($email)->notEmpty()->maxLength(50)->email();
        Assert::that($password)->notEmpty()->minLength(6)->maxLength(50);
        
        $this->id = Uuid::uuid1();
        $this->email = $email;
        $this->password = $password;
    }
}
