<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest;

use App\Shared\Application\Query\Query;
use App\Shared\Domain\DomainException;
use Symfony\Component\Messenger\Envelope;

final class QueryNotRegisteredException extends DomainException
{
    public static function from(Query|Envelope $command): self
    {
        return new self(sprintf('errors.query_not_registered %s', $command::class));
    }
}
