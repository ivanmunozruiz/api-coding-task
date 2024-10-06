<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class FactionEraser
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
    ) {
    }

    public function erase(Uuid $identifier): Faction
    {
        /** @var Faction $faction */
        $faction = $this->factionRepository->ofIdOrFail($identifier);
        $this->factionRepository->remove($identifier);
        $faction->delete();
        return $faction;
    }
}
