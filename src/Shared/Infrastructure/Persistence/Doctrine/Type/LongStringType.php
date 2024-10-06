<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;

/** @template-extends DoctrineStringType<Name> */
final class LongStringType extends DoctrineStringType
{
    protected function entityClass(): string
    {
        return StringValueObject::class;
    }
}
