<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Service\Character;

use App\LotrContext\Domain\Aggregate\Character;
use App\LotrContext\Domain\Repository\CharacterRepository;
use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;
use App\Shared\Domain\ValueObject\Uuid;

final class CharacterEraser
{
    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly RedisCacheCharacterRepository $redisCacheCharacterRepository,
    ) {
    }

    public function erase(Uuid $identifier): Character
    {
        /** @var Character $character */
        $character = $this->characterRepository->ofIdOrFail($identifier);
        $this->characterRepository->remove($identifier);
        $character->delete();
        $this->redisCacheCharacterRepository->removeData($identifier);
        return $character;
    }
}
