<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;

final class Token extends StringValueObject
{
    public const LENGTH = 255;

    protected function ensureItIsValid(string $value): void
    {
        Assertion::maxLength($value, self::LENGTH);
    }
}
