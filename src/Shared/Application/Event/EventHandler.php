<?php

declare(strict_types=1);

namespace App\Shared\Application\Event;

use App\Shared\Domain\Aggregate\DomainEvent;

/** @method __invoke(DomainEvent $domainEvent) */
interface EventHandler
{
}
