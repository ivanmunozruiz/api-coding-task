<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Event\Equipment;

use App\LotrContext\Domain\Aggregate\Equipment;

class EquipmentUpdated extends EquipmentEvent
{
    public static function fromAggregate(Equipment $equipment): self
    {
        return new self(
            $equipment->id()->id(),
            $equipment->id()->id(),
            $equipment->name()->value(),
            $equipment->type()->value(),
            $equipment->madeBy()->value(),
        );
    }
}
