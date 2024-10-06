<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception\Http;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\ValueObject\Uuid;

final class UuIdAlreadyExistsException extends DomainException implements ConflictException
{
    public static function from(Uuid $identifier): self
    {
        return new self(
            sprintf(
                'Uuid %s already exists',
                $identifier->id(),
            ),
        );
    }
}
