<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Messaging;

use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\Aggregate\Message;

final class SpyMessageBus implements EventBus
{
    /** @param array<Message> $events */
    public function __construct(private array $events = [])
    {
    }

    public function resetEvents(): void
    {
        $this->events = [];
    }

    /** @return array<Message> */
    public function events(): array
    {
        return $this->events;
    }

    public function publish(Message ...$message): void
    {
        $this->events = $message;
    }
}
