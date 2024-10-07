<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transformer;

use App\Shared\Domain\Aggregate\Message;

interface MessageToArrayTransformer
{
    /**
     * @return array{
     *     data: array{
     *      message_id: string,
     *      message_name: string,
     *      message_version: int,
     *      payload: non-empty-array<string,mixed>,
     *      occurred_on: string,
     *      },
     *     meta: array{}
     *     }
     *
     * @throws \Throwable
     */
    public function toArray(Message $message): array;
}
