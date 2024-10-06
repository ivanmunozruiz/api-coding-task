<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\Repository\DomainRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\LotrContext\Domain\Aggregate\Faction;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;

interface FactionRepository extends DomainRepository
{
    public function ofId(Uuid $identifier): ?Faction;

    public function ofNameAndDescription(Name $name, StringValueObject $description): ?Faction;
}
