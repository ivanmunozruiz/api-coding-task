<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\CreateFaction;

use App\LotrContext\Domain\Service\Faction\FactionCreator;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use App\LotrContext\Domain\Exception\Faction\FactionAlreadyExistsException;

final class CreateFactionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly FactionCreator $factionCreator,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws AssertionFailedException|FactionAlreadyExistsException
     */
    public function __invoke(CreateFactionCommand $command): void
    {
        $name = Name::from($command->factionName());
        $description = StringValueObject::from($command->description());
        $identifier = Uuid::from($command->identifier());
        $faction = $this->factionCreator->create(
            $identifier,
            $name,
            $description,
        );

        $this->eventBus->publish(...$faction->events());
    }
}
