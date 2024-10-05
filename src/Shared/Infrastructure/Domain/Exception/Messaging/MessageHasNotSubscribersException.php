<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Domain\Exception\Messaging;

use RuntimeException;

final class MessageHasNotSubscribersException extends RuntimeException
{
    public static function withMessageName(string $messageName): self
    {
        return throw new self(
            sprintf("The event <%s> doesn't exist or has no subscribers", $messageName),
        );
    }
}
