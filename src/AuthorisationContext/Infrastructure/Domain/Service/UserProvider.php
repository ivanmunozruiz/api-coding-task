<?php

declare(strict_types=1);

namespace App\AuthorisationContext\Infrastructure\Domain\Service;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Uuid;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\AuthorisationContext\Infrastructure\Domain\Aggregate\User;

/** @template-implements UserProviderInterface<User> */
final class UserProvider implements UserProviderInterface
{
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        //TODO: I know, If I have time I will implement this
        return User::create(
            Uuid::from(Uuid::random()->id()),
            Email::from('ivan.sazo@gmail.com')
        );
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     */
    public function loadUserByUsername(string $username): never
    {
        throw new Exception('Not Implemented');
    }

    /**
     * Refreshes the user after being reloaded from the session.
     * Not required for a "stateless" firewall.
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new Exception('Not Implemented');
    }

    /**
     * Tells Symfony to use this provider for this ValidTokenUserProvider class.
     */
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
