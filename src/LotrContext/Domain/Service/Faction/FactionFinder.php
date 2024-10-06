<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Exception\Faction\FactionNotFoundException;
use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;

final class FactionFinder
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
        private readonly RedisCacheFactionRepository $redisCacheFactionRepository,
    ) {
    }

    /**
     * @throws FactionNotFoundException
     */
    public function ofIdOrFail(Uuid $identifier): Faction
    {
        $faction = $this->redisCacheFactionRepository->getData($identifier);
        if (null !== $faction) {
            return Faction::from(
                Uuid::from($faction['id']),
                Name::from($faction['faction_name']),
                StringValueObject::from($faction['description']),
            );
        }

        $faction = $this->factionRepository->ofId($identifier);

        if (!$faction instanceof Faction) {
            throw FactionNotFoundException::from($identifier);
        }

        return $faction;
    }
}
