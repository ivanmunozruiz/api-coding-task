<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Formatter;

use Assert\Assertion;
use Assert\AssertionFailedException;
use App\Shared\Domain\Aggregate\Message;
use App\Shared\Domain\Service\Message\DomainEventToMessageNameConverter;
use Throwable;

final class AsyncApiDomainEventToMessageNameConverter implements DomainEventToMessageNameConverter
{
    /** @throws AssertionFailedException */
    public function __construct(private readonly string $appName = 'api-coding-task')
    {
        Assertion::notBlank($appName);
    }

    /**
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function convert(Message $domainEvent): string
    {
        return AsyncApiFormatter::format(
            $domainEvent->messageApplicationId() ?? $this->appName,
            $domainEvent,
        );
    }
}
