<?php declare(strict_types=1);

namespace System\Infractructure\Doctrine\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use System\Infractructure\Doctrine\Query\QueryFilter;
use System\Application\Exception\ResourceNotFoundException;

abstract class BaseQuery
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function createQB(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    protected function column(QueryBuilder $qb, QueryFilter $filter = null): string
    {
        $this->filter($filter, $qb);
        
        return (string) $this->connection->fetchColumn($qb->getSQL(), $qb->getParameters());
    }

    protected function assoc(QueryBuilder $qb, QueryFilter $filter = null): array
    {
        $this->filter($filter, $qb);
        
        $return = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (!$return) {
            throw new ResourceNotFoundException("Cannot get resource");
        }

        return $return;
    }

    protected function all(QueryBuilder $qb, QueryFilter $filter = null): array
    {
        $this->filter($filter, $qb);
        
        return $this->connection->fetchAll($qb->getSQL(), $qb->getParameters());
    }

    protected function filter(?QueryFilter $queryFilter, QueryBuilder $qb)
    {
        if (!is_null($queryFilter)) {
            $queryFilter->filter($qb);
        }
    }
}
