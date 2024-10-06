<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Domain\Repository;

use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Traits\Cacheable;
use Predis\Client as RedisClient;

final class RedisFactionRepository implements RedisCacheFactionRepository
{
    use Cacheable {
        Cacheable::__construct as private traitConstruct;
    }

    private const CACHE_KEY_PREFIX = 'faction:';

    private const CACHE_EXPIRY = 300;

    public function __construct(RedisClient $redis)
    {
        $this->traitConstruct($redis);
    }

    /** @return null|array{
     *     id: string,
     *     faction_name: string,
     *     description: string,
     * }
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
     * @param array{
     *     id: string,
     *     faction_name: string,
     *     description: string,
     * } $data
     */
    public function setData(Uuid $uuid, array $data): void
    {
        $key = $this->prepareStorageKey($uuid->id());
        $this->setInCache($key, json_encode($data), self::CACHE_EXPIRY);
    }

    public function removeData(Uuid $uuid): void
    {
        $key = $this->prepareStorageKey($uuid->id());
        $this->deleteCacheData($key);
    }

    private function prepareStorageKey(string $key): string
    {
        return self::CACHE_KEY_PREFIX . $key;
    }
}
