<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Infrastructure\Domain\Repository\DoctrineRepository;
use App\LotrContext\Domain\Aggregate\Faction;

/** @extends DoctrineRepository<Faction> */
final class DoctrineFactionRepository extends DoctrineRepository implements FactionRepository
{
    public function ofId(Uuid $identifier): ?Faction
    {
        return $this->repository()->findOneBy([
            'id' => $identifier->id(),
        ]);
    }

    public function ofNameAndDescription(Name $name, StringValueObject $description): ?Faction
    {
        return $this->repository()->findOneBy([
            'factionName' => $name->value(),
            'description' => $description->value(),
        ]);
    }

    public function remove(Uuid $identifier): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->delete($this->entityClass(), 'e')
            ->where('e.id = :id')
            ->setParameter('id', $identifier->id())
            ->getQuery()
            ->getSingleScalarResult();
    }


    /** @return class-string<object> */
    protected function entityClass(): string
    {
        return Faction::class;
    }
}
