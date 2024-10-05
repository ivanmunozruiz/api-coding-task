<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Throwable;
use App\Shared\Domain\Aggregate\DomainEvent;
use App\Shared\Infrastructure\Messaging\Transformer\ArrayToDomainEventTransformer;
use App\Shared\Infrastructure\Messaging\Transformer\MessageToArrayTransformer;

use function assert;

final class DomainEventSerializer implements SerializerInterface
{
    public function __construct(
        private readonly MessageToArrayTransformer $toArrayTransformer,
        private readonly ArrayToDomainEventTransformer $toDomainEventTransformer,
        private readonly JsonEncoder $encoder,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function decode(array $encodedEnvelope): Envelope
    {
        if (!isset($encodedEnvelope['body'])) {
            throw new MessageDecodingFailedException('Encoded envelope should have at least a "body".');
        }

        try {
            /** @var array{_meta?:array{retry-count?:int}, data: array{message_id: string,message_name: string,message_version: int,payload: non-empty-array<string,mixed>,occurred_on_in_atom: string,occurred_on_in_ms: int}, meta: array{}} $array */
            $array = $this->encoder->decode($encodedEnvelope['body']);
        } catch (Throwable $throwable) {
            throw new MessageDecodingFailedException(
                message: sprintf('Error when trying to json_decode message: "%s"', $encodedEnvelope['body']),
                code: 0,
                previous: $throwable,
            );
        }

        $meta = $array['meta'];

        try {
            $message = $this->toDomainEventTransformer->toMessage($array);
            $envelope = new Envelope(message: $message);
        } catch (Throwable $throwable) {
            throw new MessageDecodingFailedException(
                message: 'Failed to decode message',
                code: 0,
                previous: $throwable,
            );
        }

        return $this->addMetaToEnvelope($meta, $envelope);
    }

    /**
     * @return array{headers: array{'Content-Type':string}, body: string}
     * @throws Throwable
     */
    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);
        assert($envelope->getMessage() instanceof DomainEvent);
        $message = $this->toArrayTransformer->toArray($envelope->getMessage());
        $message['meta'] = $this->getMetaFromEnvelope($envelope);

        return [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $this->encoder->encode($message),
        ];
    }

    /** @param array{retryCount?:int} $meta */
    private function addMetaToEnvelope(array $meta, Envelope $envelope): Envelope
    {
        if (isset($meta['retryCount'])) {
            $envelope = $envelope->with(new RedeliveryStamp(retryCount: $meta['retryCount']));
        }

        return $envelope->with(new BusNameStamp('event.bus'));
    }

    /** @return array{retryCount?:int} */
    private function getMetaFromEnvelope(Envelope $envelope): array
    {
        $meta = [];

        $redeliveryStamp = $envelope->last(RedeliveryStamp::class);

        if ($redeliveryStamp instanceof RedeliveryStamp) {
            $meta['retryCount'] = $redeliveryStamp->getRetryCount();
        }

        return $meta;
    }
}
