<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Character\FetchCharacter;

use App\LotrContext\Domain\Aggregate\Character;
use App\Shared\Application\Query\QueryResponse;

final class FetchCharacterQueryResponse implements QueryResponse
{
    private function __construct(private readonly Character $equipment)
    {
    }

    public static function write(Character $equipment): self
    {
        return new self($equipment);
    }

    /** @return array{
     *     id: string,
     *     name: string,
     *     birth_date: string,
     *     kingdom: string,
     *     equipment_id: string,
     *     faction_id: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->equipment->jsonSerialize();
    }
}
