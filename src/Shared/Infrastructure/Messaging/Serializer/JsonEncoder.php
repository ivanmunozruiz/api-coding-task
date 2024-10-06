<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Serializer;

use App\Shared\Domain\Event\Encoder;
use Safe\Exceptions\JsonException;

final class JsonEncoder implements Encoder
{
    /** @throws JsonException */
    public function encode(array $data): string
    {
        return \Safe\json_encode($data);
    }

    /** @throws JsonException */
    public function decode(string $data): mixed
    {
        return \Safe\json_decode($data, true);
    }
}
