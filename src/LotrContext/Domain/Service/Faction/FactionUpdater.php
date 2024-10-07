<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Exception\Faction\FactionAlreadyExistsException;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;

final class FactionUpdater
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
        private readonly RedisCacheFactionRepository $redisCacheFactionRepository,
    ) {
    }

    /**
     * @throws FactionAlreadyExistsException
     */
    public function update(Uuid $identifier, Name $name, StringValueObject $description): Faction
    {
        /** @var Faction $faction */
        $faction = $this->factionRepository->ofIdOrFail($identifier);
        $this->ensureFactionDoesntExist($name, $description);
        $faction->update($name, $description);

        $this->factionRepository->save($faction);

        // refresh cache
        $this->redisCacheFactionRepository->removeData($identifier);
        $this->redisCacheFactionRepository->setData($identifier, $faction->jsonSerialize());

        return $faction;
    }

    private function ensureFactionDoesntExist(
        Name $name,
        StringValueObject $description,
    ): void {
        $faction = $this->factionRepository->ofNameAndDescription($name, $description);

        if ($faction instanceof Faction) {
            throw FactionAlreadyExistsException::from($name, $description);
        }
    }
}
