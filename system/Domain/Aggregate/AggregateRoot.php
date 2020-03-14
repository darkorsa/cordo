<?php

namespace System\Domain\Aggregate;

use League\Event\EventInterface;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    protected function record(EventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
