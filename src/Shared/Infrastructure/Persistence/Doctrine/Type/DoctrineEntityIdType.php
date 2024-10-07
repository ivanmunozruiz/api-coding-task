<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineEntityIdType extends GuidType
{
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        /* @phpstan-var Uuid|string|null $value */
        return $value instanceof Uuid ? $value->id() : \strval($value);
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?object
    {
        if (null === $value) {
            return null;
        }

        if (\is_string($value)) {
            return Uuid::from($value);
        }

        throw new ConversionException(sprintf('Value must be a string, %s given', get_debug_type($value)));
    }

    public function getName(): string
    {
        return 'aggregate_id';
    }
}
