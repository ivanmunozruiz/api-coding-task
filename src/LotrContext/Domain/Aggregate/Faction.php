<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Aggregate;

use App\LotrContext\Domain\Event\Faction\FactionCreated;
use App\LotrContext\Domain\Event\Faction\FactionDeleted;
use App\LotrContext\Domain\Event\Faction\FactionUpdated;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

class Faction extends AggregateRoot
{
    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        private readonly Uuid $id,
        private Name $factionName,
        private StringValueObject $description,
    ) {
        $this->recordThat(FactionCreated::fromAggregate($this));
    }

    public static function from(
        Uuid $id,
        Name $factionName,
        StringValueObject $description,
    ): self {
        return new self(
            $id,
            $factionName,
            $description
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function factionName(): Name
    {
        return $this->factionName;
    }

    public function description(): StringValueObject
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return (string) $this->factionName();
    }

    /** @return array{
     *     id: string,
     *     faction_name: string,
     *     description: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id()->id(),
            'faction_name' => $this->factionName()->value(),
            'description' => $this->description()->value(),
        ];
    }

    /**
     * @throws AssertionFailedException
     */
    public function delete(): void
    {
        $this->recordThat(FactionDeleted::fromAggregate($this));
    }

    public function update(Name $name, StringValueObject $description): void
    {
        $this->factionName = $name;
        $this->description = $description;
        $this->recordThat(FactionUpdated::fromAggregate($this));
    }
}
