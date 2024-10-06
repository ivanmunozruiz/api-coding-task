<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use Throwable;
use App\Shared\Domain\ValueObject\Uuid;

interface DomainRepository
{
    public function save(object $aggregate): void;

    public function ofId(Uuid $identifier): ?object;

    public function ofIdOrFail(Uuid $identifier): object;

    public function total(): int;
}
