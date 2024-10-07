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
            $identifier,
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId
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
        $this->redisCacheCharacterRepository->setData(
            $identifier,
            $character->jsonSerialize()
        );

        return $character;
    }

    /** @throws CharacterAlreadyExistsException|UuIdAlreadyExistsException */
    private function ensureCharacterDoesntExist(
        Uuid $identifier,
        Name $name,
        DateTimeValueObject $birthDate,
        Name $kingdom,
        Uuid $equipmentId,
        Uuid $factionId
    ): void {
        $character = $this->characterRepository->ofId($identifier);
        if ($character instanceof Character) {
            throw UuIdAlreadyExistsException::from($identifier);
        }

        $character = $this->characterRepository->findOneBy([
            'name' => $name->value(),
            'birthDate' => $birthDate->datetime()->format('Y-m-d'),
            'kingdom' => $kingdom->value(),
            'equipmentId' => $equipmentId->id(),
            'factionId' => $factionId->id()
        ]);

        if ($character instanceof Character) {
            throw CharacterAlreadyExistsException::from();
        }
    }
}
