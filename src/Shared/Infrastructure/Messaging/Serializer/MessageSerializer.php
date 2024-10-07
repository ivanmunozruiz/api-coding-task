<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Serializer;

use App\Shared\Domain\Aggregate\Message;
use App\Shared\Infrastructure\Messaging\Transformer\ArrayToMessageTransformer;
use App\Shared\Infrastructure\Messaging\Transformer\MessageToArrayTransformer;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class MessageSerializer implements SerializerInterface
{
    public function __construct(
        private readonly MessageToArrayTransformer $toArrayTransformer,
        private readonly ArrayToMessageTransformer $toDomainEventTransformer,
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
            $array = $this->encoder->decode($encodedEnvelope['body']);
        } catch (\Throwable $throwable) {
            throw new MessageDecodingFailedException(message: 'Error when trying to json_decode message', code: 0, previous: $throwable);
        }

        $meta = $array['meta'];

        try {
            $message = $this->toDomainEventTransformer->toMessage($array);
            $envelope = new Envelope(message: $message);
            $envelope = $envelope->with(new AmqpStamp($array['data']['message_name']));
        } catch (\Throwable $throwable) {
            throw new MessageDecodingFailedException(message: 'Failed to decode message', code: 0, previous: $throwable);
        }

        return $this->addMetaToEnvelope($meta, $envelope);
    }

    /**
     * @return array{headers: array{'Content-Type':string}, body: string}
     *
     * @throws \Throwable
     */
    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);
        $message = $envelope->getMessage();
        \assert($message instanceof Message);
        $message = $this->toArrayTransformer->toArray($message);
        $message['meta'] = $this->getMetaFromEnvelope($envelope) ?? [];

        return [
            'body' => $this->encoder->encode($message),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }

    /** @param array{retry_count?:int, bus_name?: string} $meta */
    private function addMetaToEnvelope(array $meta, Envelope $envelope): Envelope
    {
        if (isset($meta['retry_count'])) {
            $envelope = $envelope->with(new RedeliveryStamp(retryCount: $meta['retry_count']));
        }

        $busName = 'event.bus'; // here qe can set what bus to use

        return $envelope->with(new BusNameStamp(busName: $busName));
    }

    /** @return array{retry_count?:int, bus_name?: string}|null */
    private function getMetaFromEnvelope(Envelope $envelope): ?array
    {
        $meta = null;

        if (($stamp = $envelope->last(RedeliveryStamp::class)) instanceof RedeliveryStamp) {
            $meta['retry_count'] = $stamp->getRetryCount();
        }

        return $meta;
    }
}
