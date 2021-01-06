<?php

use Bernard\Serializer;
use Bernard\Normalizer\EnvelopeNormalizer;
use Bernard\QueueFactory\PersistentFactory;
use Normalt\Normalizer\AggregateNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Cordo\Core\Infractructure\Persistance\Bernard\Driver\QueueDriverFactory;

$driver = QueueDriverFactory::factory($container->get('config')->get('queue'));

return new PersistentFactory(
    $driver,
    new Serializer(
        new AggregateNormalizer([
            new EnvelopeNormalizer(),
            new SymfonySerializer(
                [new ObjectNormalizer()],
                [new JsonEncoder()]
            ),
        ])
    )
);
