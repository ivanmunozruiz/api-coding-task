<?php

namespace App\LotrContext\Application\Event\Equipment;

use App\LotrContext\Domain\Event\Equipment\EquipmentCreated;
use App\LotrContext\Domain\Service\Equipment\EquipmentCreator;
use App\Shared\Application\Event\EventHandler;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EquipmentCreatedHandler implements EventHandler
{
    public function __construct(private readonly EquipmentCreator $equipmentCreator)
    {
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(EquipmentCreated $domainEvent): void
    {
        $data = $domainEvent->toPrimitives();
        $this->equipmentCreator->createInCache(
            Uuid::from($domainEvent->messageAggregateId()),
            Name::from($data['name']),
            Name::from($data['type']),
            Name::from($data['madeBy'])
        );
    }
}
