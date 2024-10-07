<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Character\ListCharacter;

use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Service\Character\SearchCharactersByCriteria;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Domain\ValueObject\PositiveIntegerValueObject;
use Assert\AssertionFailedException;
use Doctrine\ORM\Query\QueryException;

/**
 * @template T of Character
 */
final class ListCharactersQueryHandler implements QueryHandler
{
    /**
     * @param SearchCharactersByCriteria<T> $criteria
     */
    public function __construct(
        private readonly SearchCharactersByCriteria $criteria
    ) {
    }

    /**
     * @throws QueryException
     * @throws AssertionFailedException
     */
    public function __invoke(ListCharactersQuery $query): ListCharactersResponse
    {
        $page = PositiveIntegerValueObject::from($query->page());
        $limit = PositiveIntegerValueObject::from($query->limit());

        $criteriaMatch = $this->criteria->matching(
            $page,
            $limit,
        );

        return ListCharactersResponse::write(
            $criteriaMatch->results(),
            $criteriaMatch->page(),
            $criteriaMatch->limit(),
            $criteriaMatch->numResults(),
        );
    }
}
