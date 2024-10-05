<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Carbon\CarbonImmutable;
use Carbon\CarbonTimeZone;
use DateTimeInterface;
use JsonSerializable;
use Stringable;
use Throwable;
use App\Shared\Domain\Equalable;

final class DateTimeValueObject implements JsonSerializable, Stringable, Equalable
{
    private const TZ = 'UTC';

    private readonly CarbonImmutable|DateTimeInterface $datetime;

    private function __construct(string $datetime, string $timezone)
    {
        $value = CarbonImmutable::parse($datetime, $timezone);
        $this->datetime = $value->toDateTime();
    }

    /** @throws AssertionFailedException */
    public static function withDatetime(string $datetime, ?string $timezone = 'UTC'): self
    {
        self::assertTimezone($timezone);
        self::assertDatetime($datetime);

        return new self(datetime: $datetime, timezone: $timezone ?? self::TZ);
    }

    public static function now(): self
    {
        $datetime = CarbonImmutable::now(self::TZ)->format(DateTimeInterface::RFC3339_EXTENDED);

        return new self(datetime: $datetime, timezone: self::TZ);
    }

    /** @throws AssertionFailedException */
    public static function fromOrNull(?string $value): ?self
    {
        return null === $value ? null : self::withDatetime($value);
    }

    /** @throws AssertionFailedException */
    public static function fromTimestamp(int $timestamp): self
    {
        return self::withDatetime(date('Y-m-d H:i:s', $timestamp));
    }

    public static function subInterval(int $interval): self
    {
        $limitDate = self::now()->datetime()->getTimestamp() - $interval;

        return self::fromTimestamp($limitDate);
    }

    /** @throws AssertionFailedException */
    public function isEqualTo(object $other): bool
    {
        Assertion::isInstanceOf($other, self::class);

        return $this->datetime()->format(DateTimeInterface::RFC3339_EXTENDED) === $other->datetime()->format(
            DateTimeInterface::RFC3339_EXTENDED,
        );
    }

    /**
     * Check the object is newer than the current dateTime minus the provided interval.
     *
     * @throws AssertionFailedException
     */
    public function isNewerThanNowSubInterval(int $interval): bool
    {
        $limitDate = self::now()->datetime()->getTimestamp() - $interval;

        return $this->datetime()->getTimestamp() > $limitDate;
    }

    public function datetime(): DateTimeInterface
    {
        return $this->datetime;
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->toRfc3339String();
    }

    public function toRfc3339String(): string
    {
        return $this->datetime->format(DateTimeInterface::RFC3339_EXTENDED);
    }

    /** @throws AssertionFailedException */
    private static function assertTimezone(?string $timezone): void
    {
        try {
            CarbonTimeZone::instance($timezone);
        } catch (Throwable) {
            throw new InvalidArgumentException(
                message: sprintf('Timezone "%s" is invalid.', $timezone),
                code: Assertion::INVALID_STRING,
            );
        }
    }

    /** @throws AssertionFailedException */
    private static function assertDatetime(string $datetime): void
    {
        $year = date_parse($datetime)['year'];

        if ((is_numeric($year) && $year < 1) || false === $year) {
            throw new InvalidArgumentException(
                message: sprintf('Date "%s" is invalid.', $datetime),
                code: Assertion::INVALID_DATE,
            );
        }
    }

    public function timestampMs(): int
    {
        return CarbonImmutable::parse($this->toRfc3339String())->getTimestampMs();
    }
}
