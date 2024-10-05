<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

use function strval;

use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_INT;

trait DataMapping
{
    /** @param array<string, mixed> $data */
    private static function getValue(array $data, string $key): mixed
    {
        if (!str_contains($key, '[')) {
            $key = str_contains($key, '.')
                ? implode('', array_map(static fn ($value): string => sprintf('[%s]', $value), explode('.', $key)))
                : sprintf('[%s]', $key);
        }

        return self::getPropertyAccessor()->isReadable($data, $key)
            ? self::getPropertyAccessor()->getValue(
                $data,
                $key,
            )
            : null;
    }

    private static function getPropertyAccessor(): PropertyAccessorInterface
    {
        return PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    /** @param array<string, mixed> $data */
    private static function getInt(array $data, string $key, int $default = 0): int
    {
        $value = self::getIntOrNull($data, $key);

        return $value ?? $default;
    }

    /** @param array<string, mixed> $data */
    private static function getIntOrNull(array $data, string $key): ?int
    {
        $value = self::getValue($data, $key);

        return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    /** @param array<string, mixed> $data */
    private static function getNonEmptyStringOrNull(array $data, string $key): ?string
    {
        $value = self::getString($data, $key);

        return '' === $value ? null : $value;
    }

    /** @param array<string, mixed> $data */
    private static function getString(array $data, string $key, string $default = ''): string
    {
        /** @var bool|float|int|resource|string|null $value */
        $value = self::getValue($data, $key);

        return strval($value ?? $default);
    }
}
