<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Exception\Character;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\NotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

final class CharacterNotFoundException extends DomainException implements NotFoundException
{
    public static function from(Uuid $identifier): self
    {
        return new self(
            sprintf(
                'Character with identifier %s not found',
                $identifier->id()
            ),
        );
    }
}
