<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Faction\ListFaction;

use App\Shared\Application\Query\Query;

final class ListFactionsQuery implements Query
{
    public function __construct(
        private readonly int $page = 1,
        private readonly int $limit = 10,
    ) {
    }

    public function page(): int
    {
        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
