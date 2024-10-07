<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Faction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\ResultsByCriteria;
use App\Shared\Domain\ValueObject\PositiveIntegerValueObject;
use Assert\AssertionFailedException;
use Doctrine\ORM\Query\QueryException;

/**
 * @template T of Faction
 */
final class SearchFactionsByCriteria
{
    public function __construct(private readonly FactionRepository $factionRepository)
    {
    }

    /**
     * @return ResultsByCriteria<Faction>
     *
     * @throws QueryException
     * @throws AssertionFailedException
     */
    public function matching(
        PositiveIntegerValueObject $page,
        PositiveIntegerValueObject $limit,
    ): ResultsByCriteria {
        $criteria = new Criteria(page: $page->value(), limit: $limit->value());

        return ResultsByCriteria::from(
            $this->factionRepository->matching($criteria), /* @phpstan-ignore-next-line */
            $this->factionRepository->count($criteria),
            $criteria->page(),
            $criteria->limit(),
        );
    }
}
