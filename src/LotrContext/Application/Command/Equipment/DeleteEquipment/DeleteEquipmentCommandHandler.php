<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Equipment\DeleteEquipment;

use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;
use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Service\Equipment\EquipmentEraser;

final class DeleteEquipmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EquipmentEraser $factionEraser,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws EquipmentNotFoundException|\Assert\AssertionFailedException
     */
    public function __invoke(DeleteEquipmentCommand $command): void
    {
        $id = Uuid::from($command->id());
        $faction = $this->factionEraser->erase($id);

        $this->eventBus->publish(...$faction->events());
    }
}
