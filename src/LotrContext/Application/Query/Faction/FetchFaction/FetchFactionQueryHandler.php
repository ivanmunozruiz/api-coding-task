<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Faction\FetchFaction;

use App\LotrContext\Domain\Exception\Faction\FactionNotFoundException;
use App\LotrContext\Domain\Service\Faction\FactionFinder;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Domain\ValueObject\Uuid;

final class FetchFactionQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly FactionFinder $factionFinder,
    ) {
    }

    /**
     * @throws FactionNotFoundException
     */
    public function __invoke(FetchFactionQuery $query): FetchFactionQueryResponse
    {
        $id = Uuid::from($query->identifier());

        $faction = $this->factionFinder->ofIdOrFail($id);

        return FetchFactionQueryResponse::write($faction);
    }
}
