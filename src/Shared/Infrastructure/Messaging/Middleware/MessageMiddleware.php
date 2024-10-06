<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Middleware;

use App\Shared\Domain\Aggregate\Message;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

final class MessageMiddleware implements MiddlewareInterface
{
    /**
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $domainEvent = $envelope->getMessage();
        Assertion::isInstanceOf($domainEvent, Message::class);

        return $stack->next()->handle($envelope, $stack);
    }
}
