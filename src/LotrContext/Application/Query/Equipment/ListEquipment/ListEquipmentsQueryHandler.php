<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Equipment\ListEquipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Service\Equipment\SearchEquipmentsByCriteria;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Domain\ValueObject\PositiveIntegerValueObject;
use Assert\AssertionFailedException;
use Doctrine\ORM\Query\QueryException;

/**
 * @template T of Equipment
 */
final class ListEquipmentsQueryHandler implements QueryHandler
{
    /**
     * @param SearchEquipmentsByCriteria<T> $criteria
     */
    public function __construct(
        private readonly SearchEquipmentsByCriteria $criteria,
    ) {
    }

    /**
     * @throws QueryException
     * @throws AssertionFailedException
     */
    public function __invoke(ListEquipmentsQuery $query): ListEquipmentsResponse
    {
        $page = PositiveIntegerValueObject::from($query->page());
        $limit = PositiveIntegerValueObject::from($query->limit());

        $criteriaMatch = $this->criteria->matching(
            $page,
            $limit,
        );

        return ListEquipmentsResponse::write(
            $criteriaMatch->results(),
            $criteriaMatch->page(),
            $criteriaMatch->limit(),
            $criteriaMatch->numResults(),
        );
    }
}
