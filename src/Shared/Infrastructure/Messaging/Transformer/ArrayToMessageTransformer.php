<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transformer;

use App\Shared\Domain\Aggregate\DomainEvent;

interface ArrayToMessageTransformer
{
    /**
     * @param array{
     *     data: array{
     *      message_id: string,
     *      message_name: string,
     *      message_version: int,
     *      payload: non-empty-array<string,mixed>,
     *      occurred_on_in_atom: string,
     *      occurred_on_in_ms: int
     *      },
     *     meta: array{
     *      retryCount?: int
     * }
     * } $payload
     */
    public function toMessage(array $payload): DomainEvent;
}
