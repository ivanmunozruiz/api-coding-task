<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;

final class RedisEquipmentRepository extends RedisCacheRepository implements RedisCacheEquipmentRepository
{
    protected const CACHE_KEY_PREFIX = 'equipment:';

    protected const CACHE_EXPIRY = 3000;
}
