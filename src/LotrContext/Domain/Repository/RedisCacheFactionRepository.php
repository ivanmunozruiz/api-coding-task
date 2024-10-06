<?php

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\ValueObject\Uuid;

interface RedisCacheFactionRepository
{
    public function setInCache(string $key, mixed $value, int $ttl): void;

    public function getCacheData(string $cacheKey): ?string;

    public function removeData(Uuid $uuid): void;

    /** @return null|array{
     *     id: string,
     *     faction_name: string,
     *     description: string,
     * }
     */
    public function getData(Uuid $uuid): ?array;

    /**
     * @param array{
     *     id: string,
     *     faction_name: string,
     *     description: string,
     * } $data
     */
    public function setData(Uuid $uuid, array $data): void;
}
