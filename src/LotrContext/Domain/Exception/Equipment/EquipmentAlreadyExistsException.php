<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Exception\Equipment;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\ConflictException;
use App\Shared\Domain\ValueObject\Name;

final class EquipmentAlreadyExistsException extends DomainException implements ConflictException
{
    public static function from(Name $name, Name $type, Name $madeBy): self
    {
        return new self(
            sprintf(
                'Equipment with name %s and type %s and made by %s already exists',
                $name->value(),
                $type->value(),
                $madeBy->value()
            ),
        );
    }
}
