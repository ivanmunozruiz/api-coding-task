<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Character\FetchCharacter;

use App\LotrContext\Domain\Service\Character\CharacterFinder;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class FetchCharacterQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CharacterFinder $characterFinder
    ) {
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(FetchCharacterQuery $query): FetchCharacterQueryResponse
    {
        $id = Uuid::from($query->identifier());
        $character = $this->characterFinder->ofIdOrFail($id);

        return FetchCharacterQueryResponse::write($character);
    }
}
