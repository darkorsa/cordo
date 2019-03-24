<?php

namespace System\Infractructure\Query;

interface QueryFilter
{
    public function applyFilter($queryBuilder): void;
}
