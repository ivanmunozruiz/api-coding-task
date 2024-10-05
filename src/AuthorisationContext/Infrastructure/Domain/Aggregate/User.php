<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Aggregate;

use App\Shared\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\Identifier;

final class User extends AggregateRoot implements UserInterface
{
    private function __construct(
        private readonly Identifier $identifier,
        private readonly Email $email
    ) {
    }

    public static function create(Identifier $identifier, Email $email): self
    {
        return new self(
            $identifier,
            $email
        );
    }


    public function identifier(): Identifier
    {
        return $this->identifier;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return (string) $this->identifier()->value();
    }

    /** @return array{
     *     identifier: string,
     *     email: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier()->value(),
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
        return (string) $this->identifier()->value();
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
