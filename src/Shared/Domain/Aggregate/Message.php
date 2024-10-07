<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\MessageExtractor;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

abstract class Message
{
    use MessageExtractor;

    protected const MESSAGE_VERSION = 1;

    protected readonly string $messageId;

    protected readonly int $messageVersion;

    protected readonly int $occurredOn;

    /** @throws AssertionFailedException */
    protected function __construct(
        private readonly string $aggregateId,
        ?string $messageId = null,
        ?int $messageVersion = null,
        ?int $occurredOn = null,
    ) {
        $this->messageId = $messageId ?? Uuid::random()->id();
        $this->messageVersion = $messageVersion ?? self::MESSAGE_VERSION;
        $this->occurredOn = $occurredOn ?? DateTimeValueObject::now()->timestampMs();
    }

    /**
     * @param array<string, mixed> $payload
     *
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

    public function occurredOn(): int
    {
        return $this->occurredOn;
    }

    public function messageVersion(): int
    {
        return $this->messageVersion;
    }
}
