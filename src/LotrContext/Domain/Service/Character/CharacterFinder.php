<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Character;

use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Repository\CharacterRepository;
use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class CharacterFinder
{
    public function __construct(
        private readonly CharacterRepository $equipmentRepository,
        private readonly RedisCacheCharacterRepository $redisCacheCharacterRepository,
    ) {
    }

    /**
     * @throws AssertionFailedException
     */
    public function ofIdOrFail(Uuid $identifier): Character
    {
        $equipment = $this->redisCacheCharacterRepository->getData($identifier);
        if (null !== $equipment) {
            return Character::from(
                Uuid::from($equipment['id']),
                Name::from($equipment['name']),
                DateTimeValueObject::from($equipment['birth_date']),
                Name::from($equipment['kingdom']),
                Uuid::from($equipment['equipment_id']),
                Uuid::from($equipment['faction_id']),
            );
        }

        /** @var Character */
        return $this->equipmentRepository->ofIdOrFail($identifier);
    }
}
