<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Exception\Faction\FactionInUseException;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\LotrContext\Domain\Service\Character\CharacterFinder;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class FactionEraser
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
        private readonly RedisCacheFactionRepository $redisCacheFactionRepository,
        private readonly CharacterFinder $characterFinder,
    ) {
    }

    /**
     * @throws AssertionFailedException
     * @throws FactionInUseException
     */
    public function erase(Uuid $identifier): Faction
    {
        /** @var Faction $faction */
        $faction = $this->factionRepository->ofIdOrFail($identifier);
        $this->ensureFactionIsNotInUse($faction);
        $this->factionRepository->remove($identifier);
        $faction->delete();
        $this->redisCacheFactionRepository->removeData($identifier);

        return $faction;
    }

    private function ensureFactionIsNotInUse(Faction $faction): void
    {
        $character = $this->characterFinder->findByFactionId($faction->id());
        if (null !== $character) {
            throw FactionInUseException::from($faction->id());
        }
    }
}
