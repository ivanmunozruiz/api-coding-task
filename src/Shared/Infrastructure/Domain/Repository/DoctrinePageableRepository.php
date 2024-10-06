<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Domain\Repository;

use Assert\AssertionFailedException;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use App\Shared\Domain\Criteria\Criteria;

use function is_array;
use function is_int;

/** @template T */
trait DoctrinePageableRepository
{
    public function __construct(
        private readonly EntityManagerInterface $dbManager,
        private readonly string $entityClass,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws QueryException
     */
    public function count(?Criteria $criteria = null): int
    {
        $queryBuilder = $this->dbManager->createQueryBuilder();
        $results = $queryBuilder->select('count(ec.id)')
            ->from($this->entityClass, 'ec') /* @phpstan-ignore-line */
            ->addCriteria(
                DoctrineCriteria::create() // here we can apply whatever filters
            )
            ->getQuery()
            ->getSingleScalarResult();

        if (!is_int($results)) {
            throw new NoResultException();
        }

        return $results;
    }

    /**
     * @return array<T>
     * @throws AssertionFailedException
     * @throws QueryException
     */
    public function matching(Criteria $criteria): array
    {
        $doctrineCriteria = $this->applyPagination($criteria);

        $results = $this->dbManager
            ->createQueryBuilder()
            ->select('ec')
            ->orderBy('ec.id', 'DESC')
            ->from($this->entityClass, 'ec') /* @phpstan-ignore-line */
            ->addCriteria($doctrineCriteria)
            ->getQuery()
            ->getResult();

        if (!is_array($results)) {
            return [];
        }

        return $results;
    }

    private function applyPagination(Criteria $criteria): DoctrineCriteria
    {
        return DoctrineCriteria::create()
            ->setFirstResult(($criteria->page() - 1) * $criteria->limit())
            ->setMaxResults($criteria->limit());
    }
}
