<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Equipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\Shared\Domain\Aggregate\DomainEventMessage;
use Assert\AssertionFailedException;

class EquipmentEvent extends DomainEventMessage
{
    private function __construct(
        string $aggregateId,
        private readonly ?string $entityId,
        private readonly string $name,
        private readonly string $type,
        private readonly string $madeBy,
        ?string $messageId = null,
        ?int $messageVersion = null,
        ?int $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $messageId, $messageVersion, $occurredOn);
    }

    /** @throws AssertionFailedException */
    public static function fromAggregate(Equipment $equipment): self
    {
        return new self(
            $equipment->id()->id(),
            $equipment->id()->id(),
            $equipment->name()->value(),
            $equipment->type()->value(),
            $equipment->madeBy()->value(),
        );
    }

    /** @throws AssertionFailedException */
    public static function fromPrimitives(
        string $aggregateId,
        array $payload,
        string $messageId,
        int $messageVersion,
        int $occurredOn,
    ): self {
        /** @phpstan-ignore-next-line  */
        return new self(
            $aggregateId,
            strval($payload['id']),
            $payload['name'],
            $payload['type'],
            $payload['madeBy'],
            $messageId,
            $messageVersion,
            $occurredOn,
        );
    }

    public function entityId(): ?string
    {
        return $this->entityId;
    }

    public function entityType(): string
    {
        return 'Equipment';
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function madeBy(): string
    {
        return $this->madeBy;
    }

    public function toPrimitives(): array
    {
        return [
            'entityId' => $this->entityId(),
            'name' => $this->name(),
            'description' => $this->type(),
            'madeBy' => $this->madeBy(),
        ];
    }

    public function aggregateName(): string
    {
        return Equipment::class;
    }
}
