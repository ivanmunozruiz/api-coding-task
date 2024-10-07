<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest;

use App\Shared\Application\Query\Query;
use App\Shared\Infrastructure\Traits\CurrentUserInRequest;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class ApiQueryPage
{
    use CurrentUserInRequest;
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /** @throws \Throwable */
    protected function ask(Query|Envelope $message): mixed
    {
        try {
            return $this->handle($message);
        } catch (NoHandlerForMessageException) {
            throw QueryNotRegisteredException::from($message);
        } catch (HandlerFailedException $handlerFailedException) {
            throw $this->raiseException($handlerFailedException);
        }
    }

    protected function raiseException(\Throwable $e): \Throwable
    {
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
            \assert($e instanceof \Throwable);
        }

        return $e;
    }
}
