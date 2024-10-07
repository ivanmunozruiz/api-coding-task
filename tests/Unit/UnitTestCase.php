<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Tests\Unit\Shared\Infrastructure\Messaging\SpyMessageBus;
use Carbon\CarbonImmutable;
use App\Shared\Domain\Aggregate\DomainEventMessage;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Matcher\Closure as MockeryClosure;
use Mockery\MockInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Application\Query\PaginatorQueryResponse;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryHandler;
use App\Shared\Application\Query\QueryResponse;

use function is_string;

abstract class UnitTestCase extends MockeryTestCase
{
    private SpyMessageBus $messageBus;

    /** @phpstan-var EventBus&MockInterface $eventBus */
    private EventBus|MockInterface $eventBus;

    /** @phpstan-var MessageBusInterface&MockInterface $commandBus */
    private MessageBusInterface|MockInterface $commandBus;

    /**
     * return a protected method from an object.
     *
     * @param class-string|object $object
     * @throws ReflectionException
     */
    public function getProtectedMethod(string|object $object, string $methodName): ReflectionMethod
    {
        $className = is_string($object) ? $object : $object::class;
        $reflectionClass = new ReflectionClass($className);
        $method = $reflectionClass->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    protected function messageBus(): SpyMessageBus
    {
        return $this->messageBus ??= new SpyMessageBus();
    }

    /** @phpstan-return EventBus&MockInterface */
    protected function eventBus(): EventBus|MockInterface
    {
        return $this->eventBus ??= Mockery::mock(EventBus::class);
    }

    /** @phpstan-return MessageBusInterface&MockInterface */
    protected function commandBus(): MessageBusInterface|MockInterface
    {
        return $this->commandBus ??= Mockery::mock(MessageBusInterface::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    protected function shouldPublishAnEvent(): void
    {
        $this->eventBus()
            ->shouldReceive('publish')
            ->once()
            ->andReturnNull();
    }

    protected function shouldPublishNEvents(int $nEvents): void
    {
        $this->eventBus()
            ->shouldReceive('publish')
            ->times($nEvents)
            ->andReturnNull();
    }

    protected function dispatch(Command|DomainEventMessage $message, callable $commandHandler): void
    {
        $commandHandler($message);
    }

    /** @phpstan-return PaginatorQueryResponse&QueryResponse */
    protected function ask(Query $query, QueryHandler $queryHandler): QueryResponse
    {
        return $queryHandler->__invoke($query);
    }

    protected function customMock(string $class): MockInterface
    {
        return Mockery::mock($class);
    }

    private function getPropertyAccessor(): PropertyAccessorInterface
    {
        return PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    /** @param array<mixed> $value */
    protected static function matchAgainstArray(array $value): MockeryClosure
    {
        return Mockery::on(static fn ($argument): bool => $argument === $value);
    }
}
