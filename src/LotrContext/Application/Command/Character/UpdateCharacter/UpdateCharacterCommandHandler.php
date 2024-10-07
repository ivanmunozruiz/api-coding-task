<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Character\UpdateCharacter;

use App\LotrContext\Domain\Service\Character\CharacterUpdater;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use App\LotrContext\Domain\Exception\Character\CharacterAlreadyExistsException;

final class UpdateCharacterCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CharacterUpdater $characterUpdater,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws AssertionFailedException|CharacterAlreadyExistsException
     */
    public function __invoke(UpdateCharacterCommand $command): void
    {
        $name = Name::from($command->name());
        $birthDate = DateTimeValueObject::from($command->birthDate());
        $kingdom = Name::from($command->kingdom());
        $equipmentId = Uuid::from($command->equipmentId());
        $factionId = Uuid::from($command->factionId());
        $identifier = Uuid::from($command->identifier());

        $character = $this->characterUpdater->update(
            $identifier,
            $name,
            $birthDate,
            $kingdom,
            $equipmentId,
            $factionId,
        );

        $this->eventBus->publish(...$character->events());
    }
}
