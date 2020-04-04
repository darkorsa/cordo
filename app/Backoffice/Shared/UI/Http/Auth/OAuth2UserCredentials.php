<?php

namespace App\Backoffice\Shared\UI\Http\Auth;

use Doctrine\DBAL\Connection;
use OAuth2\Storage\UserCredentialsInterface;

class OAuth2UserCredentials implements UserCredentialsInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function checkUserCredentials($username, $password)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('u.password')
            ->from('backoffice_user', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $username);

        $result = $this->connection->fetchAssoc($queryBuilder->getSQL(), $queryBuilder->getParameters());

        if (!$result || !password_verify($password, $result['password'])) {
            return false;
        }

        return true;
    }

    public function getUserDetails($username)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('ouuid_to_uuid(u.id_user) as id_user')
            ->from('backoffice_user', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $username);

        $userId = $this->connection->fetchColumn($queryBuilder->getSQL(), $queryBuilder->getParameters());

        return ['user_id' => $userId];
    }
}
