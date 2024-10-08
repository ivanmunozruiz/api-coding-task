<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Equipment;

use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Exception\Equipment\EquipmentInUseException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\LotrContext\Domain\Service\Character\CharacterFinder;
use App\Shared\Domain\ValueObject\Uuid;

final class EquipmentEraser
{
    public function __construct(
        private readonly EquipmentRepository $equipmentRepository,
        private readonly RedisCacheEquipmentRepository $redisCacheEquipmentRepository,
        private readonly CharacterFinder $characterFinder,
    ) {
    }

    public function erase(Uuid $identifier): Equipment
    {
        /** @var Equipment $equipment */
        $equipment = $this->equipmentRepository->ofIdOrFail($identifier);
        $this->ensureEquipmentIsNotInUse($equipment);
        $this->equipmentRepository->remove($identifier);
        $equipment->delete();
        $this->redisCacheEquipmentRepository->removeData($identifier);

        return $equipment;
    }

    private function ensureEquipmentIsNotInUse(Equipment $equipment): void
    {
        $character = $this->characterFinder->findByEquipmentId($equipment->id());
        if ($character instanceof Character) {
            throw EquipmentInUseException::from($equipment->id());
        }
    }
}
