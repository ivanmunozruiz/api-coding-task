<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Aggregate;

use App\LotrContext\Domain\Event\Character\CharacterCreated;
use App\LotrContext\Domain\Event\Character\CharacterDeleted;
use App\LotrContext\Domain\Event\Character\CharacterUpdated;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

class Character extends AggregateRoot
{
    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        private readonly Uuid $id,
        private Name $name,
        private DateTimeValueObject $birthDate,
        private Name $kingdom,
        private Uuid $equipmentId,
        private Uuid $factionId,
    ) {
        $this->recordThat(CharacterCreated::fromAggregate($this));
    }

    public static function from(
        Uuid $id,
        Name $name,
        DateTimeValueObject $birthDate,
        Name $kingdom,
        Uuid $equipmentId,
        Uuid $factionId,
    ): self {
        return new self(
            $id,
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function birthDate(): DateTimeValueObject
    {
        return $this->birthDate;
    }

    public function kingdom(): Name
    {
        return $this->kingdom;
    }

    public function equipmentId(): Uuid
    {
        return $this->equipmentId;
    }

    public function factionId(): Uuid
    {
        return $this->factionId;
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }

    /** @return array{
     *     id: string,
     *     name: string,
     *     birth_date: string,
     *     kingdom: string,
     *     equipment_id: string,
     *     faction_id: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id()->id(),
            'name' => $this->name()->value(),
            'birth_date' => $this->birthDate()->datetime()->format('Y-m-d'),
            'kingdom' => $this->kingdom()->value(),
            'equipment_id' => $this->equipmentId()->id(),
            'faction_id' => $this->factionId()->id(),
        ];
    }

    /**
     * @throws AssertionFailedException
     */
    public function delete(): void
    {
        $this->recordThat(CharacterDeleted::fromAggregate($this));
    }

    public function update(
        Name $name,
        DateTimeValueObject $birthDate,
        Name $kingdom,
        Uuid $equipmentId,
        Uuid $factionId,
    ): void {
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->kingdom = $kingdom;
        $this->equipmentId = $equipmentId;
        $this->factionId = $factionId;

        $this->recordThat(CharacterUpdated::fromAggregate($this));
    }
}
