<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Character;

use App\LotrContext\Domain\Aggregate\Character;
use App\Shared\Domain\Aggregate\DomainEventMessage;
use Assert\AssertionFailedException;

class CharacterCreated extends DomainEventMessage
{
    protected function __construct(
        string $aggregateId,
        private readonly ?string $entityId,
        private readonly string $name,
        private readonly string $birthDate,
        private readonly string $kingdom,
        private readonly string $equipmentId,
        private readonly string $factionId,
        ?string $messageId = null,
        ?int $messageVersion = null,
        ?int $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $messageId, $messageVersion, $occurredOn);
    }

    /** @throws AssertionFailedException */
    public static function fromAggregate(Character $character): self
    {
        return new self(
            $character->id()->id(),
            $character->id()->id(),
            $character->name()->value(),
            $character->birthDate()->toRfc3339String(),
            $character->kingdom()->value(),
            $character->equipmentId()->id(),
            $character->factionId()->id(),
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
            $payload['aggregate_id'],
            $payload['name'],
            $payload['birthDate'],
            $payload['kingdom'],
            $payload['equipmentId'],
            $payload['factionId'],
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
        return 'Character';
    }

    public function name(): string
    {
        return $this->name;
    }

    public function birthDate(): string
    {
        return $this->birthDate;
    }

    public function kingdom(): string
    {
        return $this->kingdom;
    }

    public function equipmentId(): string
    {
        return $this->equipmentId;
    }

    public function factionId(): string
    {
        return $this->factionId;
    }

    public function toPrimitives(): array
    {
        return [
            'entityId' => $this->entityId(),
            'name' => $this->name(),
            'birthDate' => $this->birthDate(),
            'kingdom' => $this->kingdom(),
            'equipmentId' => $this->equipmentId(),
            'factionId' => $this->factionId(),
        ];
    }

    public function aggregateName(): string
    {
        return Character::class;
    }
}
