<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Aggregate;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\Name;
use App\LotrContext\Domain\Event\Equipment\EquipmentCreated;
use App\LotrContext\Domain\Event\Equipment\EquipmentUpdated;
use App\LotrContext\Domain\Event\Equipment\EquipmentDeleted;
use Assert\AssertionFailedException;

class Equipment extends AggregateRoot
{
    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        private readonly Uuid $id,
        private Name $name,
        private Name $type,
        private Name $madeBy,
    ) {
        $this->recordThat(EquipmentCreated::fromAggregate($this));
    }

    public static function from(
        Uuid $id,
        Name $name,
        Name $type,
        Name $madeBy,
    ): self {
        return new self(
            $id,
            $name,
            $type,
            $madeBy
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

    public function type(): Name
    {
        return $this->type;
    }

    public function madeBy(): Name
    {
        return $this->madeBy;
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }

    /** @return array{
     *     id: string,
     *     name: string,
     *     type: string,
     *     made_by: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id()->id(),
            'name' => $this->name()->value(),
            'type' => $this->type()->value(),
            'made_by' => $this->madeBy()->value(),
        ];
    }

    /**
     * @throws AssertionFailedException
     */
    public function delete(): void
    {
        $this->recordThat(EquipmentDeleted::fromAggregate($this));
    }

    public function update(Name $name, Name $type, Name $madeBy): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->madeBy = $madeBy;
        $this->recordThat(EquipmentUpdated::fromAggregate($this));
    }
}
