<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Criteria\Criteria;
use Throwable;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @template T
 */
interface DomainRepository
{
    public function save(object $aggregate): void;

    public function ofId(Uuid $identifier): ?object;

    public function ofIdOrFail(Uuid $identifier): object;

    public function total(): int;

    /**
     * @return T[]
     */
    public function matching(Criteria $criteria): array;
}
