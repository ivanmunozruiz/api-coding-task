<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Domain\ValueObject;

use App\Shared\Domain\ValueObject\StringValueObject;
use Assert\Assertion;

final class CacheKey extends StringValueObject
{
    private const MIN_LENGTH = 64;

    public function key(): string
    {
        return $this->value();
    }

    protected function ensureItIsValid(string $value): void
    {
        Assertion::minLength($value, self::MIN_LENGTH);
    }
}
