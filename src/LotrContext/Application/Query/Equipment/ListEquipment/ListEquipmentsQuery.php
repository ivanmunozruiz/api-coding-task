<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Equipment\ListEquipment;

use App\Shared\Application\Query\Query;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class ListEquipmentsQuery implements Query
{
    private const MIN_PAGE = 1;

    private const MIN_LIMIT = 1;
    /**
     * @throws AssertionFailedException
     */
    public function __construct(
        private readonly int $page,
        private readonly int $limit,
    ) {
        Assertion::min(
            $page,
            self::MIN_PAGE,
            'Page should be greater than or equal to 1'
        );
        Assertion::min(
            $limit,
            self::MIN_LIMIT,
            'Size should be greater than or equal to 1'
        );
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
