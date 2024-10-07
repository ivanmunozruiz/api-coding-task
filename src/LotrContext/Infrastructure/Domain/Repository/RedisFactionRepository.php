<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;

final class RedisFactionRepository extends RedisCacheRepository implements RedisCacheFactionRepository
{
    protected const CACHE_KEY_PREFIX = 'faction:';

    protected const CACHE_EXPIRY = 3000;
}
