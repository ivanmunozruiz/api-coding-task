<?php

namespace App\LotrContext\Application\Event\Faction;

use App\LotrContext\Domain\Event\Faction\FactionCreated;
use App\LotrContext\Domain\Service\Faction\FactionCreator;
use App\Shared\Application\Event\EventHandler;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FactionCreatedHandler implements EventHandler
{
    public function __construct(private readonly FactionCreator $factionCreator)
    {
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(FactionCreated $domainEvent): void
    {
        $data = $domainEvent->toPrimitives();

        $this->factionCreator->createInCache(
            Uuid::from($domainEvent->messageAggregateId()),
            Name::from($data['name']),
            StringValueObject::from($data['description'])
        );
    }
}
