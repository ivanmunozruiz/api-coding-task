<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Character\DeleteCharacter;

use App\LotrContext\Domain\Exception\Character\CharacterNotFoundException;
use App\LotrContext\Domain\Service\Character\CharacterEraser;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteCharacterCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CharacterEraser $characterEraser,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws CharacterNotFoundException|\Assert\AssertionFailedException
     */
    public function __invoke(DeleteCharacterCommand $command): void
    {
        $id = Uuid::from($command->id());
        $character = $this->characterEraser->erase($id);

        $this->eventBus->publish(...$character->events());
    }
}
