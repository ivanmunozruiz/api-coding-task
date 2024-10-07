<?php

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\ValueObject\Uuid;

interface RedisCacheCharacterRepository
{
    public function setInCache(string $key, mixed $value, int $ttl): void;

    public function getCacheData(string $cacheKey): ?string;

    public function removeData(Uuid $uuid): void;

    /** @return null|array{
     *     id: string,
     *     name: string,
     *     birth_date: string,
     *     kingdom: string,
     *     equipment_id: string,
     *     faction_id: string,
     * }
     */
    public function getData(Uuid $uuid): ?array;

    /**
     * @param array{
     *     id: string,
     *     name: string,
     *     birth_date: string,
     *     kingdom: string,
     *     equipment_id: string,
     *     faction_id: string,
     * } $data
     */
    public function setData(Uuid $uuid, array $data): void;
}
