<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Name;

/** @template-extends DoctrineStringType<Name> */
final class NameType extends DoctrineStringType
{
    protected function entityClass(): string
    {
        return Name::class;
    }
}
