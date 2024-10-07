<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Character;

use App\LotrContext\Domain\Aggregate\Character;

class CharacterDeleted extends CharacterEvent
{
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

    public function messageAggregateAction(): string
    {
        return 'deleted';
    }
}
