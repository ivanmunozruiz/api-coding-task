<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Equipment\CreateEquipment;

use App\LotrContext\Domain\Service\Equipment\EquipmentCreator;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use App\LotrContext\Domain\Exception\Equipment\EquipmentAlreadyExistsException;

final class CreateEquipmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EquipmentCreator $equipmentCreator,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws AssertionFailedException|EquipmentAlreadyExistsException
     */
    public function __invoke(CreateEquipmentCommand $command): void
    {
        $name = Name::from($command->name());
        $type = Name::from($command->type());
        $madeBy = Name::from($command->madeBy());
        $identifier = Uuid::from($command->identifier());
        $equipment = $this->equipmentCreator->create(
            $identifier,
            $name,
            $type,
            $madeBy,
        );

        $this->eventBus->publish(...$equipment->events());
    }
}
