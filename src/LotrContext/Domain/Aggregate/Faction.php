<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Aggregate;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Traits\Updatable;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\LotrContext\Domain\Event\Faction\FactionCreated;
use App\Shared\Domain\ValueObject\StringValueObject;
use Assert\AssertionFailedException;

class Faction extends AggregateRoot
{
    use Updatable;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(
        private readonly Name $factionName,
        private readonly StringValueObject $description,
    ) {
        $this->recordThat(FactionCreated::fromAggregate($this));
    }

    public static function from(
        Name $name,
        StringValueObject $description,
        DateTimeValueObject $createdAt,
    ): self {
        return new self(
            $name,
            $description,
            $createdAt,
        );
    }

    public function name(): Name
    {
        return $this->factionName;
    }

    public function description(): StringValueObject
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return (string) $this->name();
    }

    /** @return array{
     *     id: string,
     *     name: string,
     *     description: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name()->value(),
            'description' => $this->description()->value(),
        ];
    }
}
