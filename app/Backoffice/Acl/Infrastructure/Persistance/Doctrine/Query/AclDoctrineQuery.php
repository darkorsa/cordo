<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use App\Backoffice\Acl\Application\Query\AclView;
use App\Backoffice\Acl\Application\Query\AclQuery;
use Cordo\Core\Application\Query\QueryFilterInterface;
use Cordo\Core\Infractructure\Persistance\Doctrine\Query\BaseQuery;

class AclDoctrineQuery extends BaseQuery implements AclQuery
{
    public function count(?QueryFilterInterface $aclFilter = null): int
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('count(a.id_acl)')
            ->from('backoffice_acl', 'a');

        return (int) $this->column($queryBuilder, new AclDoctrineFilter($aclFilter));
    }

    public function getById(string $id, ?QueryFilterInterface $aclFilter = null): AclView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->where('ouuid_to_uuid(a.id_acl) = :id')
            ->setParameter('id', $id);

        return $this->getOneByQuery($queryBuilder, $aclFilter);
    }

    public function getByUserId(string $userId, ?QueryFilterInterface $aclFilter = null): AclView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->where('ouuid_to_uuid(a.id_user) = :userId')
            ->setParameter('userId', $userId);

        return $this->getOneByQuery($queryBuilder, $aclFilter);
    }

    public function getAll(?QueryFilterInterface $aclFilter = null): ArrayCollection
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

    private function getOneByQuery(QueryBuilder $queryBuilder, ?QueryFilterInterface $aclFilter = null): AclView
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
