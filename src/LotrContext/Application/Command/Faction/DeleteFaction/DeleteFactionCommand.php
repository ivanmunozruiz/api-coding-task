<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\DeleteFaction;

use App\Shared\Application\Command\Command;

final class DeleteFactionCommand implements Command
{
    public function __construct(private readonly string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
