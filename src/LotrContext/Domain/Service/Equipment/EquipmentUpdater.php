<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Equipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Exception\Equipment\EquipmentAlreadyExistsException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;

final class EquipmentUpdater
{
    public function __construct(
        private readonly EquipmentRepository $equipmentRepository,
        private readonly RedisCacheEquipmentRepository $redisCacheEquipmentRepository,
    ) {
    }

    /**
     * @throws EquipmentAlreadyExistsException
     */
    public function update(Uuid $identifier, Name $name, Name $type, Name $madeBy): Equipment
    {
        /** @var Equipment $equipment */
        $equipment = $this->equipmentRepository->ofIdOrFail($identifier);
        $this->ensureEquipmentDoesntExist($equipment, $name, $type, $madeBy);
        $equipment->update($name, $type, $madeBy);

        $this->equipmentRepository->save($equipment);

        // refresh cache
        $this->redisCacheEquipmentRepository->removeData($identifier);
        $this->redisCacheEquipmentRepository->setData($identifier, $equipment->jsonSerialize());

        return $equipment;
    }

    private function ensureEquipmentDoesntExist(
        Equipment $equipment,
        Name $name,
        Name $type,
        Name $madeBy,
    ): void {
        $equipmentFind = $this->equipmentRepository->ofNameTypeAndMadeBy($name, $type, $madeBy);

        // if we found one and is the different ID that current equipment we dont allow to update
        if ($equipmentFind instanceof Equipment && !$equipment->id()->isEqualTo($equipmentFind->id())) {
            throw EquipmentAlreadyExistsException::from($name, $type, $madeBy);
        }
    }
}
