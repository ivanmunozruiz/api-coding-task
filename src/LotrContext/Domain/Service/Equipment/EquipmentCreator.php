<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Equipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Exception\Equipment\EquipmentAlreadyExistsException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\Shared\Domain\Exception\Http\UuIdAlreadyExistsException;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;

final class EquipmentCreator
{
    public function __construct(
        private readonly EquipmentRepository $equipmentRepository,
        private readonly RedisCacheEquipmentRepository $redisCacheEquipmentRepository,
    ) {
    }

    /**
     * @throws EquipmentAlreadyExistsException
     */
    public function create(Uuid $identifier, Name $name, Name $type, Name $madeBy): Equipment
    {
        $this->ensureEquipmentDoesntExist($identifier, $name, $type, $madeBy);
        $equipment = Equipment::from(
            $identifier,
            $name,
            $type,
            $madeBy
        );

        $this->equipmentRepository->save($equipment);

        return $equipment;
    }

    public function createInCache(Uuid $identifier, Name $name, Name $type, Name $madeBy): void
    {
        $equipment = Equipment::from(
            $identifier,
            $name,
            $type,
            $madeBy
        );

        $this->redisCacheEquipmentRepository->setData($identifier, $equipment->jsonSerialize());
    }

    /** @throws EquipmentAlreadyExistsException */
    private function ensureEquipmentDoesntExist(
        Uuid $identifier,
        Name $name,
        Name $type,
        Name $madeBy,
    ): void {
        $equipment = $this->equipmentRepository->ofId($identifier);
        if ($equipment instanceof Equipment) {
            throw UuIdAlreadyExistsException::from($identifier);
        }

        $equipment = $this->equipmentRepository->ofNameTypeAndMadeBy($name, $type, $madeBy);

        if ($equipment instanceof Equipment) {
            throw EquipmentAlreadyExistsException::from($name, $type, $madeBy);
        }
    }
}
