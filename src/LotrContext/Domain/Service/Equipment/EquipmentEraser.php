<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Equipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\Shared\Domain\ValueObject\Uuid;

final class EquipmentEraser
{
    public function __construct(
        private readonly EquipmentRepository $equipmentRepository,
        private readonly RedisCacheEquipmentRepository $redisCacheEquipmentRepository,
    ) {
    }

    public function erase(Uuid $identifier): Equipment
    {
        /** @var Equipment $equipment */
        $equipment = $this->equipmentRepository->ofIdOrFail($identifier);
        $this->equipmentRepository->remove($identifier);
        $equipment->delete();
        $this->redisCacheEquipmentRepository->removeData($identifier);
        return $equipment;
    }
}
