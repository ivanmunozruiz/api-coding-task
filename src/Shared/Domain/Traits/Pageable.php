<?php

declare(strict_types=1);

namespace App\Shared\Domain\Traits;

trait Pageable
{
    private readonly int $numResults;

    private readonly int $page;

    private readonly int $limit;

    public function numResults(): int
    {
        return $this->numResults;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    private function numPages(): int
    {
        $pages = (int) ceil($this->numResults() / $this->limit());

        return 0 < $pages ? $pages : 1;
    }
}
