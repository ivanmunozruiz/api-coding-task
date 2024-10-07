<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\Repository\DomainRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\LotrContext\Domain\Aggregate\Equipment;
use App\Shared\Domain\ValueObject\Name;

/**
 * @template-extends DomainRepository<Equipment>
 */
interface EquipmentRepository extends DomainRepository
{
    public function ofNameTypeAndMadeBy(Name $name, Name $type, Name $madeBy): ?Equipment;
    public function remove(Uuid $identifier): void;
}
