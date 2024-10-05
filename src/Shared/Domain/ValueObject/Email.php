<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;

final class Email extends StringValueObject
{
    public function email(): string
    {
        return $this->value();
    }

    protected function ensureItIsValid(string $value): void
    {
        parent::ensureItIsValid($value);

        Assertion::email($value);
    }
}
