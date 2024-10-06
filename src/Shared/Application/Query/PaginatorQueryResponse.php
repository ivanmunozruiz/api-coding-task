<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

use App\Shared\Domain\Traits\Pageable;

abstract class PaginatorQueryResponse implements QueryResponse
{
    use Pageable;

    /** @param array<int, object> $results */
    final protected function __construct(
        private readonly array $results,
        private readonly int $page,
        private readonly int $limit,
        private readonly int $numResults,
    ) {
    }

    /** @param array<int, object> $results */
    public static function write(array $results, int $page, int $limit, int $numResults): static
    {
        return new static(results: array_values($results), page: $page, limit: $limit, numResults: $numResults);
    }

    /** @return array{results: array<object>, meta: array{current_page: int, last_page: int, size: int, total: int}} */
    public function jsonSerialize(): array
    {
        return [
            'results' => $this->results(),
            'meta' => [
                'current_page' => $this->page(),
                'last_page' => $this->numPages(),
                'size' => $this->limit(),
                'total' => $this->numResults(),
            ],
        ];
    }

    /** @return array<object> */
    public function results(): array
    {
        return $this->results;
    }
}
