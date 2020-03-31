<?php

declare(strict_types=1);

namespace App\Backoffice\Auth\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use App\Backoffice\Auth\Application\Query\AclView;
use App\Backoffice\Auth\Application\Query\AclQuery;
use App\Backoffice\Auth\Application\Query\AclFilter;
use Cordo\Core\Infractructure\Persistance\Doctrine\Query\BaseQuery;

class AclDoctrineQuery extends BaseQuery implements AclQuery
{
    public function count(?AclFilter $aclFilter = null): int
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('count(a.id_acl)')
            ->from('backoffice_acl', 'a');

        return (int) $this->column($queryBuilder, new AclDoctrineFilter($aclFilter));
    }

    public function getById(string $id, ?AclFilter $aclFilter = null): AclView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->where('ouuid_to_uuid(a.id_acl) = :id')
            ->setParameter('id', $id);

        return $this->getOneByQuery($queryBuilder, $aclFilter);
    }

    public function getByUserId(string $userId, ?AclFilter $aclFilter = null): AclView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->where('ouuid_to_uuid(a.id_user) = :userId')
            ->setParameter('userId', $userId);

        return $this->getOneByQuery($queryBuilder, $aclFilter);
    }

    public function getAll(?AclFilter $aclFilter = null): ArrayCollection
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('a.*')
            ->addSelect('ouuid_to_uuid(a.id_acl) as id_acl')
            ->addSelect('ouuid_to_uuid(a.id_user) as id_user')
            ->from('backoffice_acl', 'a');

        $data = $this->all($queryBuilder, new AclDoctrineFilter($aclFilter));

        $collection = new ArrayCollection();
        array_map(static function (array $aclData) use ($collection) {
            $collection->add(AclView::fromArray($aclData));
        }, $data);

        return $collection;
    }

    private function getOneByQuery(QueryBuilder $queryBuilder, ?AclFilter $aclFilter = null): AclView
    {
        $queryBuilder
            ->select('a.*')
            ->addSelect('ouuid_to_uuid(a.id_acl) as id_acl')
            ->addSelect('ouuid_to_uuid(a.id_user) as id_user')
            ->from('backoffice_acl', 'a');

        $userData = $this->assoc($queryBuilder, new AclDoctrineFilter($aclFilter));

        return AclView::fromArray($userData);
    }
}
