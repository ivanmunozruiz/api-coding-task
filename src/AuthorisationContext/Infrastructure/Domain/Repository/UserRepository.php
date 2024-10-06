<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Repository;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use App\AuthorisationContext\Infrastructure\Domain\Aggregate\User;

final class UserRepository
{
    /** @throws AssertionFailedException */
    public function adminTokenUser(string $token): User
    {
        // I know... I know... I'm using the email as a token, I know it's not secure
        return User::create(
            Uuid::from(Uuid::random()->id()),
            Email::from($token)
        );
    }
}
