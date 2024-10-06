<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Aggregate;

use App\Shared\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;

final class User extends AggregateRoot implements UserInterface
{
    private function __construct(
        private readonly Uuid $identifier,
        private readonly Email $email
    ) {
    }

    public static function create(Uuid $identifier, Email $email): self
    {
        return new self(
            $identifier,
            $email
        );
    }


    public function identifier(): Uuid
    {
        return $this->identifier;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return (string) $this->identifier()->id();
    }

    /** @return array{
     *     identifier: string,
     *     email: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier()->id(),
            'email' => $this->email()->value(),
        ];
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->identifier()->id();
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
