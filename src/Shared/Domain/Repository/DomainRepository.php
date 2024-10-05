<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use Throwable;
use App\Shared\Domain\ValueObject\Identifier;

interface DomainRepository
{
    public function save(object $aggregate): void;

    public function ofId(Identifier $identifier);

    public function ofIdOrFail(Identifier $identifier);

    public function total(): int;
}
