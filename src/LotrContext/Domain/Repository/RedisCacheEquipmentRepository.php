<?php

namespace App\LotrContext\Domain\Repository;

use App\Shared\Domain\ValueObject\Uuid;

interface RedisCacheEquipmentRepository
{
    public function setInCache(string $key, mixed $value, int $ttl): void;

    public function getCacheData(string $cacheKey): ?string;

    public function removeData(Uuid $uuid): void;

    /** @return null|array{
     *     id: string,
     *     name: string,
     *     type: string,
     *     made_by: string,
     * }
     */
    public function getData(Uuid $uuid): ?array;

    /**
     * @param array{
     *     id: string,
     *     name: string,
     *     type: string,
     *     made_by: string,
     * } $data
     */
    public function setData(Uuid $uuid, array $data): void;
}
