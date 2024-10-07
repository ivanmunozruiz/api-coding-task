<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Faction;

use App\LotrContext\Domain\Aggregate\Faction;

final class FactionDeleted extends FactionEvent
{
    public static function fromAggregate(Faction $faction): self
    {
        return new self(
            $faction->id()->id(),
            $faction->id()->id(),
            $faction->factionName()->value(),
            $faction->description()->value(),
        );
    }
}
