<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\UpdateFaction;

use App\LotrContext\Domain\Exception\Faction\FactionAlreadyExistsException;
use App\LotrContext\Domain\Service\Faction\FactionUpdater;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class UpdateFactionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly FactionUpdater $factionUpdater,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws AssertionFailedException|FactionAlreadyExistsException
     */
    public function __invoke(UpdateFactionCommand $command): void
    {
        $name = Name::from($command->factionName());
        $description = StringValueObject::from($command->description());
        $identifier = Uuid::from($command->identifier());

        $faction = $this->factionUpdater->update(
            $identifier,
            $name,
            $description,
        );

        $this->eventBus->publish(...$faction->events());
    }
}
