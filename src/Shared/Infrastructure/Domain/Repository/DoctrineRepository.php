<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Domain\Repository;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ObjectRepository;

/** @template T of AggregateRoot */
abstract class DoctrineRepository
{
    /** @use DoctrinePageableRepository<T> */
    use DoctrinePageableRepository {
        DoctrinePageableRepository::__construct as private traitConstruct;
    }

    final public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
        $this->traitConstruct($entityManager, $this->entityClass());
    }

    /** @param T $aggregate */
    public function save(object $aggregate): void
    {
        $this->entityManager->persist($aggregate);
    }

    /**
     * @phpstan-return T|null
     *
     * @return T|null
     */
    public function ofId(Uuid $identifier): ?object
    {
        return $this->repository()->find((string) $identifier);
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function total(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('count(e.id)')->from($this->entityClass(), 'e');

        return \intval($queryBuilder->getQuery()->getSingleScalarResult());
    }

    /**
     * @phpstan-return T
     *
     * @return T
     *
     * @throws \Throwable
     */
    public function ofIdOrFail(Uuid $identifier): object
    {
        $content = $this->findOneBy(['id' => (string) $identifier]);

        if ($content instanceof AggregateRoot) {
            return $content;
        }

        $exceptionName = $this->exceptionClassName();

        throw $exceptionName::from($identifier);
    }

    public function exceptionClassName(): string
    {
        $classPath = explode('\\', $this->entityClass());
        $domainNamespace = implode('\\', \array_slice($classPath, 0, 3));
        $aggregateName = end($classPath);

        return sprintf(
            '%s\Exception\%s\%sNotFoundException',
            $domainNamespace,
            $aggregateName,
            $aggregateName,
        );
    }

    /** @return ObjectRepository<T> */
    protected function repository(): ObjectRepository
    {
        /* @phpstan-ignore-next-line */
        return $this->entityManager->getRepository($this->entityClass());
    }

    /** @phpstan-return class-string<object> */
    abstract protected function entityClass(): string;

    /**
     * @param array<string, mixed> $criteria the criteria
     *
     * @phpstan-return T|null
     *
     * @return T|null
     */
    protected function findOneBy(array $criteria): mixed
    {
        return $this->repository()->findOneBy($criteria);
    }

    public function remove(Uuid $identifier): void
    {
        $this->entityManager->remove($this->ofId($identifier));
    }
}
