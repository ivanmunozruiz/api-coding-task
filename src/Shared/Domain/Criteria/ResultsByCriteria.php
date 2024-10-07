<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\Traits\Pageable;

/**
 * @template T
 */
final class ResultsByCriteria
{
    use Pageable;

    /**
     * @var T[]
     */
    private array $results;

    /**
     * @param T[] $results
     */
    private function __construct(
        array $results,
        private readonly int $numResults,
        private readonly int $page,
        private readonly int $limit,
    ) {
        $this->results = $results;
    }

    /**
     * @param T[] $results
     *
     * @return self<T>
     */
    public static function from(array $results, int $numResults, int $page, int $limit): self
    {
        return new self(results: $results, numResults: $numResults, page: $page, limit: $limit);
    }

    /**
     * @return T[]
     */
    public function results(): array
    {
        return $this->results;
    }
}
