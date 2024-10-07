<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Character\CreateCharacter;

use App\Shared\Application\Command\Command;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class CreateCharacterCommand implements Command
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        private readonly string $identifier,
        private readonly string $name,
        private readonly string $birthDate,
        private readonly string $kingdom,
        private readonly string $equipmentId,
        private readonly string $factionId,
    ) {
        Assertion::notEmpty($identifier, 'id is required');
        Assertion::notEmpty($name, 'name field is required');
        Assertion::notEmpty($birthDate, 'birthDate field is required');
        Assertion::notEmpty($kingdom, 'kingdom field is required');
        Assertion::notEmpty($equipmentId, 'equipmentId field is required');
        Assertion::notEmpty($factionId, 'factionId field is required');
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function birthDate(): string
    {
        return $this->birthDate;
    }

    public function kingdom(): string
    {
        return $this->kingdom;
    }

    public function equipmentId(): string
    {
        return $this->equipmentId;
    }

    public function factionId(): string
    {
        return $this->factionId;
    }
}
