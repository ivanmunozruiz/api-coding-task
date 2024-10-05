<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\CreateFaction;

use App\LotrContext\Domain\Service\Faction\FactionCreator;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use Assert\AssertionFailedException;

final class CreateFactionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly FactionCreator $factionCreator,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(CreateFactionCommand $command): never
    {
        dd(111);
        $name = Name::from($command->factionName());
        $description = StringValueObject::from($command->description());

        $faction = $this->factionCreator->create(
            $name,
            $description,
        );

        $this->eventBus->publish(...$faction->events());
    }
}
