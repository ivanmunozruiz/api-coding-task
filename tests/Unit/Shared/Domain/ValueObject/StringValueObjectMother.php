<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject;

use Assert\AssertionFailedException;
use App\Shared\Domain\ValueObject\StringValueObject;

final class StringValueObjectMother
{
    /** @throws AssertionFailedException */
    public static function create(?string $value = null): StringValueObject
    {
        return StringValueObject::from($value ?? MotherCreator::generate()->sentence());
    }

    /** @throws AssertionFailedException */
    public static function dummy(): StringValueObject
    {
        return self::create(MotherCreator::generate()->sentence());
    }

    /** @throws AssertionFailedException */
    public static function withOnlySpaces(): StringValueObject
    {
        return self::create(str_repeat(' ', 10));
    }

    /** @throws AssertionFailedException */
    public static function with(string $value): StringValueObject
    {
        return self::create($value);
    }

    /** @throws AssertionFailedException */
    public static function withInvalidLength(): StringValueObject
    {
        return self::create(MotherCreator::generate()->realTextBetween(256, 300));
    }
}
