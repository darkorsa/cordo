<?php declare(strict_types=1);

namespace App\Auth\Application\Query;

use DateTime;

class AclView
{
    protected $id;

    protected $userId;

    protected $privileges;

    public function __construct(
        string $id,
        string $userId,
        string $privileges,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->privileges = $privileges;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromArray(array $data): AclView
    {
        return new AclView(
            $data['id_acl'],
            $data['id_user'],
            $data['privileges'],
            new DateTime($data['created_at']),
            new DateTime($data['updated_at'])
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function privileges(): array
    {
        return (array) json_decode($this->privileges);
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
