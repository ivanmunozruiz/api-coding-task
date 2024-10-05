<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\DomainException;

final class CommandNotRegisteredException extends DomainException
{
    public static function from(Command $command): self
    {
        return new self(sprintf('errors.command_not_registered %s', $command::class));
    }
}
