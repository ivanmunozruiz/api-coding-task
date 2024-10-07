<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Equipment\FetchEquipment;

use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Service\Equipment\EquipmentFinder;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class FetchEquipmentQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly EquipmentFinder $equipmentFinder,
    ) {
    }

    /**
     * @throws EquipmentNotFoundException|AssertionFailedException
     */
    public function __invoke(FetchEquipmentQuery $query): FetchEquipmentQueryResponse
    {
        $id = Uuid::from($query->identifier());

        $equipment = $this->equipmentFinder->ofIdOrFail($id);

        return FetchEquipmentQueryResponse::write($equipment);
    }
}
