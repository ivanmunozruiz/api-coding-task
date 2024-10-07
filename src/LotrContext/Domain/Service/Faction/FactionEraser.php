<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\Shared\Domain\ValueObject\Uuid;

final class FactionEraser
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
        private readonly RedisCacheFactionRepository $redisCacheFactionRepository,
    ) {
    }

    public function erase(Uuid $identifier): Faction
    {
        /** @var Faction $faction */
        $faction = $this->factionRepository->ofIdOrFail($identifier);
        $this->factionRepository->remove($identifier);
        $faction->delete();
        $this->redisCacheFactionRepository->removeData($identifier);

        return $faction;
    }
}
