<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Name;

/** @template-extends DoctrineTextType<Name> */
final class NameType extends DoctrineTextType
{
    protected function entityClass(): string
    {
        return Name::class;
    }
}
