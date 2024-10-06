<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\Shared\Domain\Exception\Http\UuIdAlreadyExistsException;
use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Exception\Faction\FactionAlreadyExistsException;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;

final class FactionCreator
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
        private readonly RedisCacheFactionRepository $redisCacheFactionRepository,
    ) {
    }

    /**
     * @throws FactionAlreadyExistsException
     */
    public function create(Uuid $identifier, Name $name, StringValueObject $description): Faction
    {
        $this->ensureFactionDoesntExist($identifier, $name, $description);
        $faction = Faction::from(
            $identifier,
            $name,
            $description,
        );

        $this->factionRepository->save($faction);
        //TODO: migrate this to async event ON CREATE
        $this->redisCacheFactionRepository->setData($identifier, $faction->jsonSerialize());

        return $faction;
    }

    /** @throws FactionAlreadyExistsException */
    private function ensureFactionDoesntExist(
        Uuid $identifier,
        Name $name,
        StringValueObject $description
    ): void {
        $faction = $this->factionRepository->ofId($identifier);
        if ($faction instanceof Faction) {
            throw UuIdAlreadyExistsException::from($identifier);
        }

        $faction = $this->factionRepository->ofNameAndDescription($name, $description);

        if ($faction instanceof Faction) {
            throw FactionAlreadyExistsException::from($name, $description);
        }
    }
}
