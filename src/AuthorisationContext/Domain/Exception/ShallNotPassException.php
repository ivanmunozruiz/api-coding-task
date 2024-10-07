<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Domain\Exception;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\ForbiddenException;

final class ShallNotPassException extends DomainException implements ForbiddenException
{
    public static function from(string $token): self
    {
        return new self(
            sprintf(
                'You shall not pass!!! with token %s',
                $token,
            ),
        );
    }
}
