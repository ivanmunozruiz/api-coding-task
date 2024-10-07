<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Character;

use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use App\Shared\Domain\DataMapping;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\LotrContext\Application\Command\Character\UpdateCharacter\UpdateCharacterCommand;
use Throwable;

final class UpdateCharacterPage extends ApiCommandPage
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
        $name = self::getString($request->request->all(), '[name]');
        $birthDate = self::getString($request->request->all(), '[birthDate]');
        $kingdom = self::getNonEmptyStringOrNull($request->request->all(), '[kingdom]');
        $equipmentId = self::getNonEmptyStringOrNull($request->request->all(), '[equipmentId]');
        $factionId = self::getNonEmptyStringOrNull($request->request->all(), '[factionId]');

        $this->dispatch(
            new UpdateCharacterCommand(
                $identifier,
                $name,
                $birthDate,
                $kingdom,
                $equipmentId,
                $factionId,
            ),
        );

        return new JsonResponse(
            [],
            Response::HTTP_OK
        );
    }
}
