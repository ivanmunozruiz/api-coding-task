<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Faction\ListFaction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\LotrContext\Domain\Service\Faction\SearchFactionsByCriteria;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Domain\ValueObject\PositiveIntegerValueObject;
use Assert\AssertionFailedException;
use Doctrine\ORM\Query\QueryException;

/**
 * @template T of Faction
 */
final class ListFactionsQueryHandler implements QueryHandler
{
    /**
     * @param SearchFactionsByCriteria<T> $criteria
     */
    public function __construct(
        private readonly SearchFactionsByCriteria $criteria,
    ) {
    }

    /**
     * @throws QueryException
     * @throws AssertionFailedException
     */
    public function __invoke(ListFactionsQuery $query): ListFactionsResponse
    {
        $page = PositiveIntegerValueObject::from($query->page());
        $limit = PositiveIntegerValueObject::from($query->limit());

        $criteriaMatch = $this->criteria->matching(
            $page,
            $limit,
        );

        return ListFactionsResponse::write(
            $criteriaMatch->results(),
            $criteriaMatch->page(),
            $criteriaMatch->limit(),
            $criteriaMatch->numResults(),
        );
    }
}
