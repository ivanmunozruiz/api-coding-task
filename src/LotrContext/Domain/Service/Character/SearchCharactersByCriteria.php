<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Character;

use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Repository\CharacterRepository;
use App\Shared\Domain\ValueObject\PositiveIntegerValueObject;
use Assert\AssertionFailedException;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\ResultsByCriteria;
use Doctrine\ORM\Query\QueryException;

/**
 * @template T of Character
 */
final class SearchCharactersByCriteria
{
    public function __construct(private readonly CharacterRepository $equipmentRepository)
    {
    }

    /**
     * @throws QueryException
     * @throws AssertionFailedException
     * @return ResultsByCriteria<Character>
     */
    public function matching(
        PositiveIntegerValueObject $page,
        PositiveIntegerValueObject $limit
    ): ResultsByCriteria {
        $criteria = new Criteria(page: $page->value(), limit: $limit->value());
        return ResultsByCriteria::from(
            $this->equipmentRepository->matching($criteria), /* @phpstan-ignore-next-line */
            $this->equipmentRepository->count($criteria),
            $criteria->page(),
            $criteria->limit(),
        );
    }
}
