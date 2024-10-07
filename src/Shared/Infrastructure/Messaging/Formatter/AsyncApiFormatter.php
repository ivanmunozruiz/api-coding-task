<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Formatter;

use App\Shared\Domain\Aggregate\Message;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class AsyncApiFormatter implements \Stringable
{
    private string $application;

    private int $messageVersion;

    private string $messageType;

    private string $aggregateName;

    private string $aggregateContext;

    private string $aggregationAction;

    /** @throws AssertionFailedException */
    private function __construct(
        string $application,
        string $aggregateContext,
        int $messageVersion,
        string $messageType,
        string $aggregateName,
        string $aggregateAction,
    ) {
        $this->setApplication($application)
            ->setAggregateContext($aggregateContext)
            ->setMessageVersion($messageVersion)
            ->setMessageType($messageType)
            ->setAggregateName($aggregateName)
            ->setAggregateAction($aggregateAction);
    }

    /**
     * @throws \Throwable
     * @throws AssertionFailedException
     */
    public static function format(string $appName, Message $domainEvent): string
    {
        return self::from(
            $appName,
            $domainEvent->messageAggregateContext(),
            $domainEvent->messageVersion(),
            $domainEvent->messageType(),
            $domainEvent->messageAggregateName(),
            $domainEvent->messageAggregateAction(),
        )->__toString();
    }

    public function __toString(): string
    {
        return $this->toAsyncApiFormat();
    }

    /** @throws AssertionFailedException */
    private static function from(
        string $application,
        string $aggregateContext,
        int $messageVersion,
        string $messageType,
        string $aggregateName,
        string $aggregateAction,
    ): self {
        return new self(
            application: $application,
            aggregateContext: $aggregateContext,
            messageVersion: $messageVersion,
            messageType: $messageType,
            aggregateName: $aggregateName,
            aggregateAction: $aggregateAction,
        );
    }

    private function toAsyncApiFormat(): string
    {
        return \sprintf(
            '%s.%s.%s.%s.%s.%s',
            $this->application,
            $this->aggregateContext,
            $this->messageVersion,
            $this->messageType,
            $this->aggregateName,
            $this->aggregationAction,
        );
    }

    /** @throws AssertionFailedException */
    private function setAggregateAction(string $aggregationAction): void
    {
        Assertion::notBlank($aggregationAction);
        $this->aggregationAction = trim($aggregationAction);
    }

    /** @throws AssertionFailedException */
    private function setAggregateName(string $aggregateName): self
    {
        Assertion::notBlank($aggregateName);
        $this->aggregateName = trim($aggregateName);

        return $this;
    }

    /** @throws AssertionFailedException */
    private function setMessageType(string $type): self
    {
        Assertion::choice($type, ['command', 'domain-event', 'integration-event', 'query', 'query-response']);
        $this->messageType = $type;

        return $this;
    }

    /** @throws AssertionFailedException */
    private function setMessageVersion(int $messageVersion): self
    {
        Assertion::greaterThan($messageVersion, 0);
        $this->messageVersion = $messageVersion;

        return $this;
    }

    /** @throws AssertionFailedException */
    private function setAggregateContext(string $aggregateContext): self
    {
        Assertion::notBlank($aggregateContext);
        $this->aggregateContext = trim($aggregateContext);

        return $this;
    }

    private function setApplication(string $application): self
    {
        $this->application = trim($application);

        return $this;
    }
}
