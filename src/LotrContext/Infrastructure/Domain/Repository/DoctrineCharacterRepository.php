<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Repository\CharacterRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Domain\Repository\DoctrineRepository;

/** @extends DoctrineRepository<Character> */
final class DoctrineCharacterRepository extends DoctrineRepository implements CharacterRepository
{
    public function remove(Uuid $identifier): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->delete($this->entityClass(), 'e')
            ->where('e.id = :id')
            ->setParameter('id', $identifier->id())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findOneBy(array $criteria): ?Character
    {
        return $this->repository()->findOneBy($criteria);
    }

    /** @return class-string<object> */
    protected function entityClass(): string
    {
        return Character::class;
    }
}
