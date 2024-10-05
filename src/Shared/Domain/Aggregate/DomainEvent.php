<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use Assert\AssertionFailedException;
use Throwable;
use App\Shared\Domain\ClassFunctions;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Identifier;

abstract class DomainEvent
{
    protected const MESSAGE_VERSION = 1;

    private readonly string $messageId;

    private readonly int $messageVersion;

    private readonly string $occurredOn;

    /** @throws AssertionFailedException */
    protected function __construct(
        private readonly string $aggregateId,
        ?string $messageId = null,
        ?int $messageVersion = null,
        ?string $occurredOn = null,
    ) {
        $this->messageId = $messageId ?? Identifier::random()->value();
        $this->messageVersion = $messageVersion ?? self::MESSAGE_VERSION;
        $this->occurredOn = $occurredOn ?? DateTimeValueObject::now()->toRfc3339String();
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
        string $occurredOn,
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

    public function messageAggregateContext(): string
    {
        $classNameSplit = explode('\\', $this->aggregateName());
        $aggregateContext = ClassFunctions::toKebabCase($classNameSplit[1]);

        return str_replace('-context', '', $aggregateContext);
    }

    abstract public function aggregateName(): string;

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function messageVersion(): int
    {
        return $this->messageVersion;
    }

    public function messageType(): string
    {
        return 'domain-event';
    }

    /** @throws Throwable */
    public function messageAggregateAction(): string
    {
        $value = ClassFunctions::extractClassName($this);
        $actionName = ClassFunctions::toKebabCase($value);

        return str_replace($this->messageAggregateName() . '-', '', $actionName);
    }

    public function messageAggregateName(): string
    {
        $aggregateName = ClassFunctions::extractClassNameFromString($this->aggregateName(), '');

        return ClassFunctions::toKebabCase($aggregateName);
    }
}
