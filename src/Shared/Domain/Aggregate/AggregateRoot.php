<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

abstract class AggregateRoot implements \JsonSerializable, \Stringable
{
    /** @var array<Message> */
    private array $events = [];

    /** @return array<Message> */
    final public function events(): array
    {
        return $this->events;
    }

    final public function resetEvents(): void
    {
        $this->events = [];
    }

    final protected function recordThat(Message $event): void
    {
        $this->events[] = $event;
    }
}
