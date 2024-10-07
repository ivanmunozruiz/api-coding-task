<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Domain\Aggregate;

use App\LotrContext\Domain\Aggregate\Faction;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\StringValueObjectMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Assert\AssertionFailedException;

final class FactionMother
{
    /** @throws AssertionFailedException */
    public static function create(
        ?Uuid $id = null,
        ?Name $name = null,
        ?StringValueObject $description = null,
    ): Faction {
        return Faction::from(
            $id ?? UuidMother::dummy(),
            $name ?? NameMother::dummy(),
            $description ?? StringValueObjectMother::dummy(),
        );
    }
}
