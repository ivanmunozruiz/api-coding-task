<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Exception\Character;

use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\ConflictException;

final class CharacterAlreadyExistsException extends DomainException implements ConflictException
{
    public static function from(): self
    {
        return new self('Already exists a character with all same data');
    }
}
