<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Character;

use App\LotrContext\Domain\Repository\CharacterRepository;
use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;
use App\Shared\Domain\Exception\Http\UuIdAlreadyExistsException;
use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Exception\Character\CharacterAlreadyExistsException;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;

final class CharacterCreator
{
    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly RedisCacheCharacterRepository $redisCacheCharacterRepository,
    ) {
    }

    /**
     * @throws CharacterAlreadyExistsException|UuIdAlreadyExistsException
     */
    public function create(
        Uuid $identifier,
        Name $name,
        DateTimeValueObject $birthDate,
        Name $kingdom,
        Uuid $equipmentId,
        Uuid $factionId
    ): Character {
        $this->ensureCharacterDoesntExist(
            $identifier
        );
        $character = Character::from(
            $identifier,
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId
        );

        $this->characterRepository->save($character);
        //TODO: migrate this to async event ON CREATE


        return $character;
    }

    public function createInCache(
        Uuid $identifier,
        Name $name,
        DateTimeValueObject $birthDate,
        Name $kingdom,
        Uuid $equipmentId,
        Uuid $factionId
    ): void {
        $character = Character::from(
            $identifier,
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId
        );

        $this->redisCacheCharacterRepository->setData(
            $identifier,
            $character->jsonSerialize()
        );
    }

    /** @throws UuIdAlreadyExistsException */
    private function ensureCharacterDoesntExist(
        Uuid $identifier
    ): void {
        $character = $this->characterRepository->ofId($identifier);
        if ($character instanceof Character) {
            throw UuIdAlreadyExistsException::from($identifier);
        }
    }
}
