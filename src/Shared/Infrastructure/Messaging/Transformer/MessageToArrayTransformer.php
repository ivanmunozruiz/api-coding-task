<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transformer;

use Throwable;
use App\Shared\Domain\Aggregate\DomainEvent;

interface MessageToArrayTransformer
{
    /**
     * @return array{
     *     data: array{
     *      message_id: string,
     *      message_name: string,
     *      message_version: int,
     *      payload: non-empty-array<string,mixed>,
     *      occurred_on_in_atom: string,
     *      occurred_on_in_ms: int
     *      },
     *     meta: array{}
     *     }
     * @throws Throwable
     */
    public function toArray(DomainEvent $domainEvent): array;
}
