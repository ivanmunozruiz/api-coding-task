<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Faction\DeleteFaction;

use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Messaging\Bus\EventBus;
use App\Shared\Domain\ValueObject\Uuid;
use App\LotrContext\Domain\Exception\Faction\FactionNotFoundException;
use App\LotrContext\Domain\Service\Faction\FactionEraser;
use App\LotrContext\Domain\Event\Faction\FactionDeleted;

final class DeleteFactionCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly FactionEraser $factionEraser,
        private readonly EventBus $eventBus,
    ) {
    }

    /**
     * @throws FactionNotFoundException|\Assert\AssertionFailedException
     */
    public function __invoke(DeleteFactionCommand $command): void
    {
        $id = Uuid::from($command->id());
        $this->factionEraser->erase($id);

        // publish event
        $this->eventBus->publish(
            FactionDeleted::fromPrimitives(
                aggregateId: $id->id(),
                payload: ['id' => $id->id()],
                messageId: $id->id(),
                messageVersion: 1,
                occurredOn: time(),
            )
        );
    }
}
