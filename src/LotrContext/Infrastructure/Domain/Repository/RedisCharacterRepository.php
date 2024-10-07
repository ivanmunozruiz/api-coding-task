<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;

final class RedisCharacterRepository extends RedisCacheRepository implements RedisCacheCharacterRepository
{
    protected const CACHE_KEY_PREFIX = 'character:';

    protected const CACHE_EXPIRY = 3000;
}
