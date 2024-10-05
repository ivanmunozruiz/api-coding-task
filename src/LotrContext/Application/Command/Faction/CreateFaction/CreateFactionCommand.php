<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\CreateFaction;

use Assert\Assertion;
use App\Shared\Application\Command\Command;

final class CreateFactionCommand implements Command
{
    public function __construct(
        private readonly string $factionName,
        private readonly string $description,
    ) {
        Assertion::notEmpty($factionName, 'The faction name is required');
        Assertion::notEmpty($description, 'The description is required');
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
