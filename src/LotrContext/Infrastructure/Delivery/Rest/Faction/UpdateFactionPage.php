<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Faction;

use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use App\Shared\Domain\DataMapping;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\LotrContext\Application\Command\Faction\UpdateFaction\UpdateFactionCommand;
use Throwable;

final class UpdateFactionPage extends ApiCommandPage
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
        $this->currentUserId($request); // only for info
        $identifier = self::getString($request->attributes->all(), '[id]');
        $contentData = json_decode(
            json: $request->getContent(),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );
        $name = self::getString($contentData, 'name');
        $description = self::getString($contentData, 'description');

        $this->dispatch(
            new UpdateFactionCommand(
                $identifier,
                $name,
                $description,
            ),
        );

        return new JsonResponse(
            [],
            Response::HTTP_OK
        );
    }
}
