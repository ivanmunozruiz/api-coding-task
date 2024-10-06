<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Faction\FetchFaction;

use App\LotrContext\Domain\Aggregate\Faction;
use App\Shared\Application\Query\QueryResponse;

final class FetchFactionQueryResponse implements QueryResponse
{
    private function __construct(private readonly Faction $faction)
    {
    }

    public static function write(Faction $faction): self
    {
        return new self($faction);
    }

    /** @return array{
     *     id: string,
     *     faction_name: string,
     *     description: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->faction->jsonSerialize();
    }
}
