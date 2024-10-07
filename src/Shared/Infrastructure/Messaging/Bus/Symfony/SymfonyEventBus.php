<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Bus\Symfony;

use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\Aggregate\Message;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

use function Lambdish\Phunctional\each;

final class SymfonyEventBus implements EventBus
{
    public function __construct(private readonly MessageBusInterface $eventBus)
    {
    }

    public function publish(Message ...$message): void
    {
        each($this->publisher(), $message);
    }

    private function publisher(): \Closure
    {
        return fn (Message $domainEvent): Envelope => $this->eventBus->dispatch($domainEvent);
    }
}
