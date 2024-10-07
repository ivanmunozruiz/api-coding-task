<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Equipment;

use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use App\Shared\Domain\DataMapping;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\LotrContext\Application\Command\Equipment\UpdateEquipment\UpdateEquipmentCommand;
use Throwable;

final class UpdateEquipmentPage extends ApiCommandPage
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
        $type = self::getString($request->request->all(), '[type]');
        $madeBy = self::getNonEmptyStringOrNull($request->request->all(), '[madeBy]');

        $this->dispatch(
            new UpdateEquipmentCommand(
                $identifier,
                $name,
                $type,
                $madeBy,
            ),
        );

        return new JsonResponse(
            [],
            Response::HTTP_OK
        );
    }
}
