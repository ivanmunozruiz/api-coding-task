<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Infrastructure\Domain\Repository\DoctrineRepository;
use App\LotrContext\Domain\Aggregate\Equipment;

/** @extends DoctrineRepository<Equipment> */
final class DoctrineEquipmentRepository extends DoctrineRepository implements EquipmentRepository
{
    public function ofNameTypeAndMadeBy(
        Name $name,
        Name $type,
        Name $madeBy
    ): ?Equipment {
        return $this->repository()->findOneBy([
            'name' => $name->value(),
            'type' => $type->value(),
            'madeBy' => $madeBy->value(),
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
        return Equipment::class;
    }
}
