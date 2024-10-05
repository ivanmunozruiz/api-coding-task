<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\Repository\DomainRepository;
use App\Shared\Domain\ValueObject\Identifier;
use App\LotrContext\Domain\Aggregate\Faction;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;

/** @extends DomainRepository<Faction> */
interface FactionRepository extends DomainRepository
{
    public function ofId(Identifier $identifier): ?Faction;

    public function ofNameAndDescription(Name $name, StringValueObject $description): ?Faction;
}
