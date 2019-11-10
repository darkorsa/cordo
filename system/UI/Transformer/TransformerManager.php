<?php

declare(strict_types=1);

namespace System\UI\Transformer;

use Exception;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use League\Fractal\Serializer\JsonApiSerializer;

class TransformerManager implements TransformerManagerInterface
{
    private $transformers = [];

    public function add(TransformerAbstract $transformer, string $index): void
    {
        if (array_key_exists($index, $this->transformers)) {
            throw new Exception("Transformer already present for index \"{$index}\"");
        }

        $this->transformers[$index] = $transformer;
    }

    public function transform($result, string $index): array
    {
        if (empty($result)) {
            return $result;
        }

        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer((string) getenv('APP_URL')));

        return $fractal->createData($this->resource($result, $index))->toArray();
    }

    private function get(string $index): TransformerAbstract
    {
        if (!array_key_exists($index, $this->transformers)) {
            throw new Exception("Transformer has not been set for index \"{$index}\"");
        }
        return $this->transformers[$index];
    }

    private function resource($result, $index): ResourceInterface
    {
        if ($result instanceof ArrayCollection) {
            return new Collection($result, $this->get($index), $index);
        }

        return new Item($result, $this->get($index), $index);
    }
}
