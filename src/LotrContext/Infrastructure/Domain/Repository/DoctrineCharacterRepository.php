<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\CharacterRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Domain\Repository\DoctrineRepository;
use App\LotrContext\Domain\Aggregate\Character;

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

    /** @return class-string<object> */
    protected function entityClass(): string
    {
        return Character::class;
    }
}
