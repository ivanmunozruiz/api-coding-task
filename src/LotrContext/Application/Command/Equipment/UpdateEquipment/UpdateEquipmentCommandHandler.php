<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Equipment\UpdateEquipment;

use App\LotrContext\Domain\Service\Equipment\EquipmentUpdater;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use App\LotrContext\Domain\Exception\Equipment\EquipmentAlreadyExistsException;

final class UpdateEquipmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EquipmentUpdater $equipmentUpdater,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws AssertionFailedException|EquipmentAlreadyExistsException
     */
    public function __invoke(UpdateEquipmentCommand $command): void
    {
        $name = Name::from($command->name());
        $type = Name::from($command->type());
        $madeBy = Name::from($command->madeBy());
        $identifier = Uuid::from($command->identifier());

        $equipment = $this->equipmentUpdater->update(
            $identifier,
            $name,
            $type,
            $madeBy,
        );

        $this->eventBus->publish(...$equipment->events());
    }
}
