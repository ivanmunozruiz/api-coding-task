<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Middleware;

use App\Shared\Domain\Aggregate\Message;
use App\Shared\Infrastructure\Messaging\Formatter\AsyncApiDomainEventToMessageNameConverter;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Throwable;

final class RoutingKeyMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly AsyncApiDomainEventToMessageNameConverter $toMessageNameConverter)
    {
    }

    /** @throws Throwable */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if ($message instanceof Message) {
            $envelope = $envelope->withoutStampsOfType(AmqpStamp::class);
            $envelope = $envelope->with(new AmqpStamp($this->toMessageNameConverter->convert($message)));
        } else {
            $envelope = $envelope->withoutStampsOfType(BusNameStamp::class);
        }


        return $stack->next()->handle($envelope, $stack);
    }
}
