<?php

namespace Cordo\Core\SharedKernel\Uuid\Helper;

use Ramsey\Uuid\FeatureSet;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\Generator\DefaultTimeGenerator;
use Ramsey\Uuid\Converter\Time\PhpTimeConverter;
use Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Ramsey\Uuid\Provider\Time\SystemTimeProvider;

class UuidFactoryHelper
{
    public static function getUuidFactory(): UuidFactory
    {
        $uuidFactory = new UuidFactory(new FeatureSet(false, false, false, true, false));
        $uuidFactory->setTimeGenerator(new DefaultTimeGenerator(
            new RandomNodeProvider(),
            new PhpTimeConverter(),
            new SystemTimeProvider()
        ));

        return $uuidFactory;
    }
}
