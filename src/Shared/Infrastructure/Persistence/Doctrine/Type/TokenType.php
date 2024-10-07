<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Token;

final class TokenType extends StringType
{
    private const FIELD_ID = 'token';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = Token::LENGTH;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Name ? (string) $value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Token
    {
        if (null === $value) {
            return null;
        }

        // phpcs:disable
        try {
            /**
             * @var string $value
             */
            return Token::from($value);
        } catch (AssertionFailedException) {
            return null;
        }

        // phpcs:enable
    }

    public function getName(): string
    {
        return self::FIELD_ID;
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
