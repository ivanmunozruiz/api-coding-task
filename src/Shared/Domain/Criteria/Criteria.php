<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Criteria
{
    private const DEFAULT_PAGE = 1;

    private const DEFAULT_LIMIT = 20;

    private int $limit;

    private int $page;

    /** @throws AssertionFailedException */
    public function __construct(
        ?int $page,
        ?int $limit,
    ) {
        $this->setPage($page ?? self::DEFAULT_PAGE);
        $this->setLimit($limit ?? self::DEFAULT_LIMIT);
    }

    public function page(): int
    {
        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    /** @throws AssertionFailedException */
    private function setPage(int $page): void
    {
        Assertion::greaterOrEqualThan($page, 1);
        Assertion::lessThan($page, \PHP_INT_MAX);
        $this->page = $page;
    }

    /** @throws AssertionFailedException */
    private function setLimit(int $limit): void
    {
        Assertion::greaterOrEqualThan($limit, 1);
        Assertion::lessThan($limit, \PHP_INT_MAX);
        $this->limit = $limit;
    }
}
