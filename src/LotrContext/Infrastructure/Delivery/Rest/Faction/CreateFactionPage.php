<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Faction;

use App\LotrContext\Application\Command\Faction\CreateFaction\CreateFactionCommand;
use App\Shared\Domain\DataMapping;
use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateFactionPage extends ApiCommandPage
{
    use DataMapping;

    public function __construct(
        MessageBusInterface $commandBus,
    ) {
        parent::__construct($commandBus);
    }

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $this->currentUserId($request); // only for info
        $identifier = self::getString($request->request->all(), '[id]');
        $name = self::getString($request->request->all(), '[name]');
        $description = self::getString($request->request->all(), '[description]');
        $this->dispatch(
            new CreateFactionCommand(
                $identifier,
                $name,
                $description,
            ),
        );

        return new JsonResponse(
            [],
            Response::HTTP_CREATED
        );
    }
}
