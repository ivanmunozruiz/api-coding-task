<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Middleware;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;
use App\Shared\Domain\Aggregate\DomainEvent;

final class DomainEventMiddleware implements MiddlewareInterface
{
    /**
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $domainEvent = $envelope->getMessage();
        Assertion::isInstanceOf($domainEvent, DomainEvent::class);

        return $stack->next()->handle($envelope, $stack);
    }
}
