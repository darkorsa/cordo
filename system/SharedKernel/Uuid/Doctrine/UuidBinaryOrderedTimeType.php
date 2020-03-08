<?php

namespace System\SharedKernel\Uuid\Doctrine;

use System\SharedKernel\Uuid\Helper\UuidFactoryHelper;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType as BaseUuidBinaryOrderedTimeType;

class UuidBinaryOrderedTimeType extends BaseUuidBinaryOrderedTimeType
{
    private $factory;

    protected function getUuidFactory()
    {
        if (null === $this->factory) {
            $this->factory = UuidFactoryHelper::getUuidFactory();
        }

        return $this->factory;
    }
}
