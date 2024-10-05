<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\Identifier;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Infrastructure\Domain\Repository\DoctrineRepository;
use App\LotrContext\Domain\Aggregate\Faction;

/** @extends DoctrineRepository<Faction> */
final class DoctrineFactionRepository extends DoctrineRepository implements FactionRepository
{
    public function ofId(Identifier $identifier): ?Faction
    {
        return $this->repository()->findOneBy([
            'id' => $identifier->value(),
        ]);
    }

    public function ofNameAndDescription(Name $name, StringValueObject $description): Faction
    {
        return $this->repository()->findOneBy([
            'name' => $name->value(),
            'description' => $description->value(),
            'deletedAt' => null,
        ]);
    }

    /** @return class-string<object> */
    protected function entityClass(): string
    {
        return Faction::class;
    }
}
