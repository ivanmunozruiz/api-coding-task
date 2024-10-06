<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Exception\Faction\FactionNotFoundException;
use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;

final class FactionEraser
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
    ) {
    }

    public function erase(Uuid $identifier): void
    {
        $faction = $this->factionRepository->ofId($identifier);

        if (!$faction instanceof Faction) {
            throw FactionNotFoundException::from($identifier);
        }

        $this->factionRepository->remove($identifier);
    }
}
