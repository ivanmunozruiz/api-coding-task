<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Name;
use Assert\AssertionFailedException;

final class NameMother
{
    /** @throws AssertionFailedException */
    public static function create(?string $value = null): Name
    {
        return Name::from($value ?? MotherCreator::generate()->word());
    }

    /** @throws AssertionFailedException */
    public static function dummy(): Name
    {
        return self::create(MotherCreator::generate()->word());
    }

    /** @throws AssertionFailedException */
    public static function withOnlySpaces(): Name
    {
        return self::create(str_repeat(' ', 10));
    }

    /** @throws AssertionFailedException */
    public static function with(string $value): Name
    {
        return self::create($value);
    }

    /** @throws AssertionFailedException */
    public static function withInvalidLength(): Name
    {
        return self::create(MotherCreator::generate()->realTextBetween(256, 300));
    }
}
