<?php

declare(strict_types=1);

namespace App\LotrContext\Infrastructure\Delivery\Rest\Equipment;

use App\LotrContext\Application\Command\Equipment\DeleteEquipment\DeleteEquipmentCommand;
use App\Shared\Domain\DataMapping;
use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class DeleteEquipmentPage extends ApiCommandPage
{
    use DataMapping;

    public function __construct(MessageBusInterface $commandBus)
    {
        parent::__construct($commandBus);
    }

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $id = self::getString($request->attributes->all(), '[id]');

        $this->dispatch(
            new DeleteEquipmentCommand($id),
        );

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
