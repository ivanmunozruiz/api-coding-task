<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use App\Shared\Domain\ClassFunctions;

use function is_object;
use function is_string;
use function strval;

/** @template T */
abstract class DoctrineStringType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (is_object($value) && method_exists($value, 'value')) {
            return $value->value();
        }

        $className = $this->entityClass();

        if ($value instanceof $className || is_string($value)) {
            /** @phpstan-var resource|string $value */
            return strval($value);
        }

        throw new ConversionException(
            sprintf(
                'Value must be an instance of %s or a string, %s given',
                $className,
                get_debug_type($value),
            ),
        );
    }

    public function getName(): string
    {
        $className = ClassFunctions::extractClassNameFromString($this->entityClass());

        return ClassFunctions::toSnakeCase($className);
    }

    public function name(): string
    {
        return $this->getName();
    }

    /**
     * @phpstan-return T|null
     * @return T|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }

        try {
            /** @var T $className */
            $className = $this->entityClass();

            /** @phpstan-var resource|string $value */
            return $className::fromOrNull(strval($value));
        } catch (AssertionFailedException) {
            throw new ConversionException(
                sprintf(
                    'Value must be an instance of %s or a string, %s given',
                    $this->entityClass(),
                    get_debug_type($value),
                ),
            );
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

    /** @phpstan-return class-string<object> */
    abstract protected function entityClass(): string;
}
