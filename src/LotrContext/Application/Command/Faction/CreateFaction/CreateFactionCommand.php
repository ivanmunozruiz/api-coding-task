<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\CreateFaction;

use App\Shared\Application\Command\Command;
use Assert\Assertion;

final class CreateFactionCommand implements Command
{
    public function __construct(
        private readonly string $identifier,
        private readonly string $factionName,
        private readonly string $description,
    ) {
        Assertion::notEmpty($identifier, 'id is required');
        Assertion::notEmpty($factionName, 'name field is required');
        Assertion::notEmpty($description, 'description field is required');
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function factionName(): string
    {
        return $this->factionName;
    }

    public function description(): string
    {
        return $this->description;
    }
}
