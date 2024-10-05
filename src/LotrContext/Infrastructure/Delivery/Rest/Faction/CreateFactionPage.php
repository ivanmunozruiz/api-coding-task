<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Faction;

use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use App\Shared\Domain\DataMapping;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\LotrContext\Application\Command\Faction\CreateFaction\CreateFactionCommand;
use Throwable;

final class CreateFactionPage extends ApiCommandPage
{
    use DataMapping;

    public function __construct(
        MessageBusInterface $commandBus
    ) {
        parent::__construct($commandBus);
    }

    /** @throws Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $requesterId = $this->currentUserId($request); // only for info
        $name = self::getString($request->request->all(), '[name]');
        $description = self::getString($request->request->all(), '[description]');
        try {
            $this->dispatch(
                new CreateFactionCommand(
                    $name,
                    $description,
                ),
            );
        } catch (Throwable $throwable) {
            // here maybe we can log some metrics in DataDog or whatever
            throw $throwable;
        }

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
