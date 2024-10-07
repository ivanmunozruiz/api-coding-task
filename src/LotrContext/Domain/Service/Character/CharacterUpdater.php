<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Character;

use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Exception\Faction\FactionNotFoundException;
use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;
use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Repository\CharacterRepository;
use App\LotrContext\Domain\Service\Equipment\EquipmentFinder;
use App\LotrContext\Domain\Service\Faction\FactionFinder;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;

final class CharacterUpdater
{
    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly RedisCacheCharacterRepository $redisCacheCharacterRepository,
        private readonly FactionFinder $factionFinder,
        private readonly EquipmentFinder $equipmentFinder,
    ) {
    }

    /**
     * @throws FactionNotFoundException
     * @throws EquipmentNotFoundException
     */
    public function update(
        Uuid $identifier,
        Name $name,
        DateTimeValueObject $birthDate,
        Name $kingdom,
        Uuid $equipmentId,
        Uuid $factionId
    ): Character {
        /** @var Character $character */
        $character = $this->characterRepository->ofIdOrFail($identifier);
        $this->ensureFactionExists($factionId);
        $this->ensureEquipmentExists($equipmentId);
        $character->update(
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId
        );

        $this->characterRepository->save($character);

        // refresh cache
        $this->redisCacheCharacterRepository->removeData($identifier);
        $this->redisCacheCharacterRepository->setData($identifier, $character->jsonSerialize());

        return $character;
    }

    /**
     * @throws FactionNotFoundException
     */
    private function ensureFactionExists(Uuid $factionId): void
    {
        $this->factionFinder->ofIdOrFail($factionId);
    }

    /**
     * @throws EquipmentNotFoundException
     */
    private function ensureEquipmentExists(Uuid $equipmentId): void
    {
        $this->equipmentFinder->ofIdOrFail($equipmentId);
    }
}
