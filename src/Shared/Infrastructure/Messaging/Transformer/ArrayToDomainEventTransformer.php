<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transformer;

use Assert\Assertion;
use Assert\AssertionFailedException;
use App\Shared\Domain\Aggregate\DomainEvent;
use App\Shared\Infrastructure\Domain\Exception\Messaging\MessageHasNotSubscribersException;

use function Lambdish\Phunctional\get;

final class ArrayToDomainEventTransformer implements ArrayToMessageTransformer
{
    /** @param iterable<string, string> $mappings */
    public function __construct(private readonly iterable $mappings = [])
    {
    }

    /**
     * @throws AssertionFailedException
     * @throws MessageHasNotSubscribersException
     */
    public function toMessage(array $payload): DomainEvent
    {
        $messageName = $payload['data']['message_name'];

        $eventClass = get($messageName, $this->mappings);

        Assertion::string($eventClass);

        if (!is_a($eventClass, DomainEvent::class, true)) {
            throw MessageHasNotSubscribersException::withMessageName($messageName);
        }

        /** @var array<string, string> $body */
        $body = $payload['data']['payload'];
        $aggregateId = $body['aggregate_id'];

        return $eventClass::fromPrimitives(
            $aggregateId,
            $body,
            $payload['data']['message_id'],
            $payload['data']['message_version'],
            $payload['data']['occurred_on_in_atom'],
        );
    }
}
