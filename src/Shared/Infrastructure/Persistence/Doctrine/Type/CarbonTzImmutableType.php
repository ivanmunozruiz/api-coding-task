<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\DateTimeValueObject;
use Carbon\CarbonImmutable;
use Carbon\Doctrine\CarbonDoctrineType;
use Carbon\Doctrine\CarbonTypeConverter;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzImmutableType as DateTimeTzType;

final class CarbonTzImmutableType extends DateTimeTzType implements CarbonDoctrineType
{
    /** @use CarbonTypeConverter<CarbonImmutable> */
    use CarbonTypeConverter;

    /**
     * @template T of DateTimeInterface
     * @throws ConversionException
     */
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
        return 'carbon_tz_immutable';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    protected function getCarbonClassName(): string
    {
        return CarbonImmutable::class;
    }
}
