<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service\Message;

use App\Shared\Domain\Aggregate\Message;

interface DomainEventToMessageNameConverter
{
    public function convert(Message $domainEvent): string;
}
