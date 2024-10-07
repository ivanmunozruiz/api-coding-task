<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Domain\Aggregate;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Assert\AssertionFailedException;

final class EquipmentMother
{
    /** @throws AssertionFailedException */
    public static function create(
        ?Uuid $id = null,
        ?Name $name = null,
        ?Name $type = null,
        ?Name $madeBy = null,
    ): Equipment {
        return Equipment::from(
            $id ?? UuidMother::dummy(),
            $name ?? NameMother::dummy(),
            $type ?? NameMother::dummy(),
            $madeBy ?? NameMother::dummy(),
        );
    }
}
