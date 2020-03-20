<?php

namespace Cordo\Core\SharedKernel\Uuid\Doctrine;

use Cordo\Core\SharedKernel\Uuid\Helper\UuidFactoryHelper;
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
