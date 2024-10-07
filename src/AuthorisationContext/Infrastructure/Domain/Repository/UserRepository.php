<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Repository;

use App\AuthorisationContext\Domain\ValueObject\CacheKey;
use App\AuthorisationContext\Infrastructure\Domain\Aggregate\User;
use App\Shared\Domain\ValueObject\Token;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Traits\Cacheable;
use Assert\AssertionFailedException;
use Predis\Client as RedisClient;

final class UserRepository
{
    use Cacheable {
        Cacheable::__construct as private traitConstruct;
    }

    private const CACHE_KEY_PREFIX = 'token:';

    private const CACHE_EXPIRY = 30;

    private const HASH_ALGO = 'sha256';

    public function __construct(
        RedisClient $redis,
        private readonly string $adminId,
    ) {
        $this->traitConstruct($redis);
    }

    public function findOneByToken(string $token): ?User
    {
        if ('' === trim($token)) {
            return null;
        }

        $storageKey = $this->prepareStorageKey($token);

        return $this->getTokenCacheData($storageKey);
    }

    /** @throws AssertionFailedException */
    public function adminTokenUser(string $token): User
    {
        $storageKey = $this->prepareStorageKey($token);
        $validTokenUser = $this->getTokenCacheData($storageKey);

        if (!$validTokenUser instanceof User) {
            $user = User::create(
                CacheKey::from($storageKey),
                Uuid::from($this->adminId),
                Token::from($token),
            );

            $this->setInCache($storageKey, $user, self::CACHE_EXPIRY);

            return $user;
        }

        return $validTokenUser;
    }

    public function prepareStorageKey(string $token): string
    {
        return self::CACHE_KEY_PREFIX . hash(self::HASH_ALGO, $token);
    }

    public function getTokenCacheData(string $cacheKey): ?User
    {
        $cacheResult = $this->redis->get($cacheKey);

        if (is_string($cacheResult) && '' !== $cacheResult) {
            /** @var array{'key': string, 'uuid': string, 'token': string}|null $dataArr */
            $dataArr = json_decode($cacheResult, true);

            if (is_array($dataArr)) {
                return User::create(
                    CacheKey::from($dataArr['key']),
                    Uuid::from($dataArr['uuid']),
                    Token::from($dataArr['token']),
                );
            }
        }

        return null;
    }
}
