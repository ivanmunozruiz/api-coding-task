<?php

namespace App\LotrContext\Domain\Exception\Equipment;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\BadRequestException;
use App\Shared\Domain\ValueObject\Uuid;

class EquipmentInUseException extends DomainException implements BadRequestException
{
    public static function from(Uuid $uuid): self
    {
        return new self(
            sprintf(
                'Equipment with id %s is in use',
                $uuid
            ),
        );
    }
}
