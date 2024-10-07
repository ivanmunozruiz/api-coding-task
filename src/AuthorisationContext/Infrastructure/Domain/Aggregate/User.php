<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Aggregate;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Token;
use App\AuthorisationContext\Domain\ValueObject\CacheKey;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;

final class User extends AggregateRoot implements UserInterface
{
    private function __construct(
        private readonly CacheKey $key,
        private readonly Uuid $uuid,
        private readonly Token $token,
    ) {
    }

    public static function create(CacheKey $key, Uuid $uuid, Token $token): self
    {
        return new self(
            $key,
            $uuid,
            $token,
        );
    }

    public function key(): CacheKey
    {
        return $this->key;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function token(): Token
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return $this->key()->value();
    }

    /** @return array{
     *     key: string,
     *     token: string,
     *     uuid: string
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key()->value(),
            'token' => $this->token()->value(),
            'uuid' => $this->uuid()->id(),
        ];
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->key()->value();
    }

    /**
     * @see UserInterface
     * @codeCoverageIgnore
     */
    public function eraseCredentials(): void
    {
        // Not necessary for this implementation
    }

    /**
     * @see UserInterface
     * @return array<string> $roles
     */
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }
}
