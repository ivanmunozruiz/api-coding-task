<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Character\DeleteCharacter;

use App\Shared\Application\Command\Command;
use Assert\Assertion;

final class DeleteCharacterCommand implements Command
{
    public function __construct(private readonly string $id)
    {
        Assertion::notEmpty($id, 'id is required');
    }

    public function id(): string
    {
        return $this->id;
    }
}
