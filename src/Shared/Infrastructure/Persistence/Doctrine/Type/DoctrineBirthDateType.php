<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ClassFunctions;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use Assert\AssertionFailedException;
use Carbon\CarbonImmutable;
use Carbon\Doctrine\CarbonTypeConverter;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class DoctrineBirthDateType extends Type
{
    /** @use CarbonTypeConverter<CarbonImmutable> */
    use CarbonTypeConverter;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if ($value instanceof DateTimeValueObject) {
            return $value->datetime()->format('Y-m-d');
        }

        throw new ConversionException(
            'Value must be an instance of DateTimeInterface',
        );
    }

    public function getName(): string
    {
        $className = ClassFunctions::extractClassNameFromString($this->entityClass());

        return ClassFunctions::toSnakeCase($className);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeValueObject
    {
        if (null === $value) {
            return null;
        }

        try {
            /** @var DateTimeValueObject $className */
            $className = $this->entityClass();

            return $className::from(($value));
        } catch (AssertionFailedException) {
            throw new ConversionException('Invalid value');
        }
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function entityClass(): string
    {
        return DateTimeValueObject::class;
    }
}
