<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Identifier implements ValueObject
{
    final public const LIMIT = 1;

    protected int $number;

    /** @throws AssertionFailedException */
    final private function __construct(int $number)
    {
        $this->changeNumber($number);
    }

    /** @throws AssertionFailedException */
    public static function fromOrNull(?int $value): ?static
    {
        return null === $value ? null : new static($value);
    }

    /** @throws AssertionFailedException */
    public static function from(int $number): static
    {
        return new static($number);
    }

    public function isEqualTo(object $other): bool
    {
        return $this instanceof $other && method_exists($other, 'value') && $this->value() === $other->value();
    }

    public function value(): int
    {
        return $this->number;
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }

    public function jsonSerialize(): int
    {
        return $this->number;
    }

    /** @throws AssertionFailedException */
    private function changeNumber(int $number): void
    {
        Assertion::greaterOrEqualThan($number, self::LIMIT);
        $this->number = $number;
    }

    /** @throws AssertionFailedException */
    public static function random(): self
    {
        return new self(random_int(self::LIMIT, PHP_INT_MAX));
    }
}
