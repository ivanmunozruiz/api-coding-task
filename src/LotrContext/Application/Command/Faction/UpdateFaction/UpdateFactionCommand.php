<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\UpdateFaction;

use App\Shared\Application\Command\Command;
use Assert\Assertion;

final class UpdateFactionCommand implements Command
{
    public function __construct(
        private readonly string $identifier,
        private readonly string $factionName,
        private readonly string $description,
    ) {
        Assertion::notEmpty($identifier, 'The id is required');
        Assertion::notEmpty($factionName, 'The faction name is required');
        Assertion::notEmpty($description, 'The description is required');
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
