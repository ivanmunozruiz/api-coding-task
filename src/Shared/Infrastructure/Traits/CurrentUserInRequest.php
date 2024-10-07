<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Traits;

use App\AuthorisationContext\Infrastructure\Domain\Service\Authenticator;
use Symfony\Component\HttpFoundation\Request;

trait CurrentUserInRequest
{
    protected function currentUserId(Request $request): string
    {
        /** @phpstan-var string $userId */
        $userId = $request->attributes->get(Authenticator::REQUEST_USER_ID);

        return \strval($userId);
    }
}
