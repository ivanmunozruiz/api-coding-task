<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Domain\Aggregate;

use App\LotrContext\Domain\Aggregate\Character;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Assert\AssertionFailedException;

final class CharacterMother
{
    /** @throws AssertionFailedException */
    public static function create(
        ?Uuid $id = null,
        ?Name $name = null,
        ?DateTimeValueObject $birthDate = null,
        ?Name $kingdom = null,
        ?Uuid $factionId = null,
        ?Uuid $equipmentId = null,
    ): Character {
        return Character::from(
            $id ?? UuidMother::dummy(),
            $name ?? NameMother::dummy(),
            $birthDate ?? DateTimeValueObject::from('2956-03-01'),
            $kingdom ?? NameMother::dummy(),
            $equipmentId ?? UuidMother::dummy(),
            $factionId ?? UuidMother::dummy(),
        );
    }
}
