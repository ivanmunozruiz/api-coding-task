<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Exception\Faction;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\ConflictException;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;

final class FactionAlreadyExistsException extends DomainException implements ConflictException
{
    public static function from(Name $name, StringValueObject $description): self
    {
        return new self(
            sprintf(
                'Faction with name %s and description %s already exists',
                $name->value(),
                $description->value()
            ),
        );
    }
}
