<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\Repository\DomainRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\LotrContext\Domain\Aggregate\Faction;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;

/**
 * @template-extends DomainRepository<Faction>
 */
interface FactionRepository extends DomainRepository
{
    public function ofNameAndDescription(Name $name, StringValueObject $description): ?Faction;

    public function remove(Uuid $identifier): void;
}
