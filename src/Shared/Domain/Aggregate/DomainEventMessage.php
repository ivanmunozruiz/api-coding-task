<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

abstract class DomainEventMessage extends Message
{
    public function messageType(): string
    {
        return 'domain-event';
    }
}
