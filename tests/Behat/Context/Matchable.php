<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Assert\Assertion;
use Assert\AssertionFailedException;

trait Matchable
{
    /** @throws AssertionFailedException */
    public function matchFieldAgainstCurrent(string|int|null $text, string $actual): void
    {
        match ($text) {
            '=uuid' => Assertion::uuid($actual),
            '=datetimeWithRFC3339ExtendedFormat' => Assertion::date($actual, \DateTimeInterface::RFC3339_EXTENDED),
            '=datetimeWithATOMFormat' => Assertion::date($actual, \DateTimeInterface::ATOM),
            '=timestamp' => Assertion::numeric($actual),
            default => Assertion::eq($text, $actual),
        };
    }
}
