<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Transformer;

use App\Shared\Domain\Aggregate\Message;
use App\Shared\Infrastructure\Messaging\Formatter\AsyncApiDomainEventToMessageNameConverter;

final class DomainMessageToArrayTransformer implements MessageToArrayTransformer
{
    public function __construct(
        private readonly AsyncApiDomainEventToMessageNameConverter $domainEventToMessageConverter
    ) {
    }

    public function toArray(Message $message): array
    {
        $payload = $message->toPrimitives();
        $payload['aggregate_id'] = $message->messageAggregateId();

        return [
            'data' => [
                'message_id' => $message->messageId(),
                'message_name' => $this->domainEventToMessageConverter->convert($message),
                'message_version' => $message->messageVersion(),
                'occurred_on' => (string) $message->occurredOn(),
                'payload' => $payload,
            ],
            'meta' => [],
        ];
    }
}
