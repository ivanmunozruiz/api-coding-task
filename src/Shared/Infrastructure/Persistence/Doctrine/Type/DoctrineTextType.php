<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ClassFunctions;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;

/** @template T */
abstract class DoctrineTextType extends TextType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (\is_object($value) && method_exists($value, 'value')) {
            return $value->value();
        }

        $className = $this->entityClass();

        if ($value instanceof $className || \is_string($value)) {
            /* @phpstan-var resource|string $value */
            return \strval($value); // @phpstan-ignore-line
        }

        throw new ConversionException('Value must be a string or an instance of ' . $className);
    }

    public function getName(): string
    {
        $className = ClassFunctions::extractClassNameFromString($this->entityClass());

        return ClassFunctions::toSnakeCase($className);
    }

    /**
     * @phpstan-return T|null
     *
     * @return T|null
     *
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

            /* @phpstan-var resource|string $value */
            return $className::fromOrNull(\strval($value));
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

    /** @phpstan-return class-string<object> */
    abstract protected function entityClass(): string;
}
