<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transformer;

use App\Shared\Domain\Aggregate\Message;
use App\Shared\Infrastructure\Messaging\Exception\InvalidEventClassNameProvided;

final class ArrayToDomainMessageTransformer implements ArrayToMessageTransformer
{
    /** @param iterable<string, string> $mappings */
    public function __construct(private iterable $mappings = [])
    {
    }

    /** @throws InvalidEventClassNameProvided */
    public function toMessage(array $payload): Message
    {
        $messageName = $payload['data']['message_name'];
        /** @phpstan-ignore-next-line */
        $eventClass = $this->mappings[$messageName] ?? null;
        if (null === $eventClass) {
            throw new InvalidEventClassNameProvided($messageName);
        }

        /** @var array<string, string> $body */
        $body = $payload['data']['payload'];
        $aggregateId = $body['aggregate_id'];

        return $eventClass::fromPrimitives(
            $aggregateId,
            $body,
            $payload['data']['message_id'],
            $payload['data']['message_version'],
            (int) $payload['data']['occurred_on'], /* @phpstan-ignore-line */
        );
    }
}
