<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Traits\Cacheable;
use Predis\Client as RedisClient;

abstract class RedisCacheRepository
{
    protected const CACHE_KEY_PREFIX = 'redis:';

    protected const CACHE_EXPIRY = 300;


    use Cacheable {
        Cacheable::__construct as private traitConstruct;
    }

    public function __construct(RedisClient $redis)
    {
        $this->traitConstruct($redis);
    }

    /**
     * @return array<mixed>|null
     */
    public function getData(Uuid $uuid): ?array
    {
        $key = $this->prepareStorageKey($uuid->id());
        $cacheData = $this->getCacheData($key);

        if (null === $cacheData) {
            return null;
        }

        return json_decode($cacheData, true);
    }

    /**
     * @param array<mixed> $data
     */
    public function setData(Uuid $uuid, array $data): void
    {
        $key = $this->prepareStorageKey($uuid->id());
        $this->setInCache($key, json_encode($data), static::CACHE_EXPIRY);
    }

    public function removeData(Uuid $uuid): void
    {
        $key = $this->prepareStorageKey($uuid->id());
        $this->deleteCacheData($key);
    }

    private function prepareStorageKey(string $key): string
    {
        return static::CACHE_KEY_PREFIX . $key;
    }
}
