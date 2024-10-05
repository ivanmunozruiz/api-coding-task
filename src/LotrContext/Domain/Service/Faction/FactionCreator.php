<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Exception\Faction\FactionAlreadyExistsException;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;

final class FactionCreator
{
    public function __construct(
        private readonly FactionRepository $factionRepository,
    ) {
    }

    /**
     * @throws FactionAlreadyExistsException
     */
    public function create(Name $name, StringValueObject $description): Faction
    {
        $this->ensureFactionDoesntExist($name, $description);

        $faction = Faction::from(
            $name,
            $description,
            DateTimeValueObject::now(),
        );

        $this->factionRepository->save($faction);

        return $faction;
    }

    /** @throws FactionAlreadyExistsException */
    private function ensureFactionDoesntExist(Name $name, StringValueObject $description): void
    {
        $faction = $this->factionRepository->ofNameAndDescription($name, $description);

        if ($faction instanceof Faction) {
            throw FactionAlreadyExistsException::from($name, $description);
        }
    }
}
