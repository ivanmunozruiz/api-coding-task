<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Faction;

use App\Shared\Domain\Aggregate\DomainEventMessage;
use Assert\AssertionFailedException;
use App\LotrContext\Domain\Aggregate\Faction;

use function strval;

final class FactionDeleted extends DomainEventMessage
{
    private function __construct(
        string $aggregateId,
        private readonly ?string $entityId,
        ?string $messageId = null,
        ?int $messageVersion = null,
        ?int $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $messageId, $messageVersion, $occurredOn);
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

    public function toPrimitives(): array
    {
        return [
            'entityId' => $this->entityId(),
        ];
    }

    public function aggregateName(): string
    {
        return Faction::class;
    }
}
