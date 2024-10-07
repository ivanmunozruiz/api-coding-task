<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Equipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\Shared\Domain\ValueObject\PositiveIntegerValueObject;
use Assert\AssertionFailedException;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\ResultsByCriteria;
use Doctrine\ORM\Query\QueryException;

/**
 * @template T of Equipment
 */
final class SearchEquipmentsByCriteria
{
    public function __construct(private readonly EquipmentRepository $equipmentRepository)
    {
    }

    /**
     * @throws QueryException
     * @throws AssertionFailedException
     * @return ResultsByCriteria<Equipment>
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
