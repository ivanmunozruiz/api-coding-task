<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use Throwable;

abstract class DomainEvent
{
    /**
     * @param array<string, mixed> $payload
     */
    protected function __construct(
        private readonly string $aggregateId,
        private readonly string $messageId,
        private readonly int $messageVersion,
        protected readonly array $payload,
        private readonly int $occurredOn,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     * @return static
     */
    abstract public static function fromPrimitives(
        string $aggregateId,
        array $payload,
        string $messageId,
        int $messageVersion,
        int $occurredOn,
    ): self;

    /** @return array<string, mixed> */
    abstract public function toPrimitives(): array;

    public function messageAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function messageId(): string
    {
        return $this->messageId;
    }

    abstract public function aggregateName(): string;

    public function occurredOn(): int
    {
        return $this->occurredOn;
    }

    public function messageVersion(): int
    {
        return $this->messageVersion;
    }


    abstract public function messageName(): string;

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
