<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Aggregate;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\Name;
use App\LotrContext\Domain\Event\Faction\FactionCreated;
use App\Shared\Domain\ValueObject\StringValueObject;
use Assert\AssertionFailedException;

class Faction extends AggregateRoot
{
    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        private readonly Uuid $id,
        private readonly Name $factionName,
        private readonly StringValueObject $description,
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
}
