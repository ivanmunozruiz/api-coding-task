<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Exception\Faction\FactionNotFoundException;
use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\Uuid;

final class FactionFinder
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
    ) {
    }

    /**
     * @throws FactionNotFoundException
     */
    public function ofIdOrFail(Uuid $identifier): Faction
    {
        $faction = $this->factionRepository->ofId($identifier);

        if (!$faction instanceof Faction) {
            throw FactionNotFoundException::from($identifier);
        }

        return $faction;
    }
}
