<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Faction;

use Assert\Assert;
use Assert\AssertionFailedException;
use App\Shared\Domain\Aggregate\DomainEventMessage;
use App\Shared\Domain\Event\InternalEvent;
use App\LotrContext\Domain\Aggregate\Faction;

use function strval;

final class FactionCreated extends DomainEventMessage
{
    private function __construct(
        string $aggregateId,
        private readonly ?string $entityId,
        private readonly string $factionName,
        private readonly string $description,
        ?string $messageId = null,
        ?int $messageVersion = null,
        ?int $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $messageId, $messageVersion, $occurredOn);
    }

    /** @throws AssertionFailedException */
    public static function fromAggregate(Faction $faction): self
    {
        return new self(
            (string) $faction->identifier()->value(),
            (string) $faction->identifier()->value(),
            $faction->name()->value(),
            $faction->description()->value(),
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
        return new self(
            $aggregateId,
            strval($payload['id']),
            $payload['name'],
            $payload['description'],
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
        return 'Faction';
    }

    public function name(): string
    {
        return $this->factionName;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function toPrimitives(): array
    {
        return [
            'entityId' => $this->entityId(),
            'name' => $this->name(),
            'description' => $this->description(),
        ];
    }

    public function aggregateName(): string
    {
        return Faction::class;
    }
}
