<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Uuid;

final class UuidMother
{
    public static function create(?string $value = null): Uuid
    {
        return Uuid::from($value ?? MotherCreator::generate()->uuid());
    }

    public static function createOrEmpty(?string $value = null): ?Uuid
    {
        return null !== $value ? Uuid::from($value) : null;
    }

    public static function empty(): Uuid
    {
        return Uuid::from('');
    }

    public static function withOnlySpaces(): Uuid
    {
        return Uuid::from(str_repeat(' ', 10));
    }

    public static function withInvalidId(): Uuid
    {
        return Uuid::from(MotherCreator::generate()->realTextBetween(1, 50));
    }

    public static function dummy(): Uuid
    {
        return Uuid::from(MotherCreator::generate()->unique()->uuid());
    }

    public static function withId(string $id): Uuid
    {
        return Uuid::from($id);
    }

    public static function random(): Uuid
    {
        return Uuid::random();
    }
}
