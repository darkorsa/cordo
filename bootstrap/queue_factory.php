<?php

use Bernard\Serializer;
use Bernard\Driver\PhpRedis\Driver;
use Bernard\Normalizer\EnvelopeNormalizer;
use Bernard\QueueFactory\PersistentFactory;
use Normalt\Normalizer\AggregateNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

$redis = new Redis();
$redis->connect('127.0.0.1', getenv('REDIS_PORT'));
$redis->setOption(Redis::OPT_PREFIX, 'bernard:');

$driver = new Driver($redis);

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
