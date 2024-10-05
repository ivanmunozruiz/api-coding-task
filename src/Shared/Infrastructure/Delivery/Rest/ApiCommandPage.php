<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest;

use Assert\InvalidArgumentException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;
use ValueError;
use App\Shared\Application\Command\Command;
use App\Shared\Infrastructure\Traits\CurrentUserInRequest;

use function assert;

abstract class ApiCommandPage
{
    use CurrentUserInRequest;
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /** @throws Throwable */
    protected function dispatch(Command $message): mixed
    {
        try {
            return $this->handle($message);
        } catch (NoHandlerForMessageException) {
            throw CommandNotRegisteredException::from($message);
        } catch (HandlerFailedException $handlerFailedException) {
            throw $this->raiseException($handlerFailedException);
        }
    }

    protected function raiseException(Throwable $e): Throwable
    {
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
            assert($e instanceof Throwable);
        }

        if ($e instanceof ValueError) {
            throw new InvalidArgumentException(message: $e->getMessage(), code: $e->getCode());
        }

        return $e;
    }
}
