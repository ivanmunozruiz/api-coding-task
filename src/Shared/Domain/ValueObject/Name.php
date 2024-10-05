<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Name implements ValueObject
{
    protected string $value;

    private const LIMIT = 128;

    /** @throws AssertionFailedException */
    final private function __construct(string $value)
    {
        $this->setValue($value);
    }

    /** @throws AssertionFailedException */
    public static function from(string $value): static
    {
        return new static($value);
    }

    /** @throws AssertionFailedException */
    public static function fromOrNull(?string $value): ?static
    {
        return null === $value ? null : new static($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function isEqualTo(object $other): bool
    {
        return $this instanceof $other && method_exists($other, 'value') && $this->value() === $other->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    /** @throws AssertionFailedException */
    protected function setValue(string $value): void
    {
        $this->ensureItIsValid($value);
        $this->value = trim($value);
    }

    /** @throws AssertionFailedException */
    protected function ensureItIsValid(string $value): void
    {
        Assertion::notBlank($value);
        Assertion::maxLength($value, self::LIMIT);
    }
}
