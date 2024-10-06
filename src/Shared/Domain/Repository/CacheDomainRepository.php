<?php

namespace App\Shared\Domain\Repository;

interface CacheDomainRepository
{
    public function setInCache(string $key, mixed $value, int $ttl): void;

    public function getCacheData(string $cacheKey): ?string;
}
