<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Equipment;

use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class EquipmentFinder
{
    public function __construct(
        private readonly EquipmentRepository $equipmentRepository,
        private readonly RedisCacheEquipmentRepository $redisCacheEquipmentRepository,
    ) {
    }

    /**
     * @throws EquipmentNotFoundException|AssertionFailedException
     */
    public function ofIdOrFail(Uuid $identifier): Equipment
    {
        $equipment = $this->redisCacheEquipmentRepository->getData($identifier);
        if (null !== $equipment) {
            return Equipment::from(
                Uuid::from($equipment['id']),
                Name::from($equipment['name']),
                Name::from($equipment['type']),
                Name::from($equipment['made_by']),
            );
        }

        $equipment = $this->equipmentRepository->ofId($identifier);

        if (!$equipment instanceof Equipment) {
            throw EquipmentNotFoundException::from($identifier);
        }

        return $equipment;
    }
}
