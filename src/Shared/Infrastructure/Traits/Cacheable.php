<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Traits;

use Predis\Client as RedisClient;

use function is_string;

trait Cacheable
{
    public function __construct(private readonly RedisClient $redis)
    {
    }

    public function setInCache(string $key, mixed $value, int $ttl): void
    {
        $rawCacheData = is_string($value) ? $value : json_encode($value);
        $this->redis->set($key, $rawCacheData);
        $this->redis->expire($key, $ttl);
    }

    public function getCacheData(string $cacheKey): ?string
    {
        $cacheResult = $this->redis->get($cacheKey);

        if (!is_string($cacheResult)) {
            return null;
        }

        if ('' === $cacheResult) {
            return null;
        }

        return $cacheResult;
    }

    public function deleteCacheData(string $cacheKey): void
    {
        $this->redis->del($cacheKey);
    }
}
