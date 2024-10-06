<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class Uuid implements ValueObject
{
    private string $value;

    /** @throws AssertionFailedException */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function random(): self
    {
        return new self(SymfonyUuid::v4()->toRfc4122());
    }

    public function id(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEqualTo(object $other): bool
    {
        return $other instanceof self && $this->id() === $other->id();
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /** @throws AssertionFailedException */
    public function setValue(string $value): void
    {
        Assertion::uuid($value);

        $this->value = $value;
    }
}
