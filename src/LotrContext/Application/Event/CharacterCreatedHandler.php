<?php

namespace App\LotrContext\Application\Event;

use App\LotrContext\Domain\Event\Character\CharacterCreated;
use App\LotrContext\Domain\Message\Character\CharacterCreatedMessage;
use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;
use App\LotrContext\Domain\Service\Character\CharacterCreator;
use App\Shared\Application\Event\EventHandler;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CharacterCreatedHandler implements EventHandler
{
    public function __construct(private readonly CharacterCreator $characterCreator)
    {
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(CharacterCreated $domainEvent): void
    {
        $data = $domainEvent->toPrimitives();

        $this->characterCreator->createInCache(
            Uuid::from($domainEvent->messageAggregateId()),
            Name::from($data['name']),
            DateTimeValueObject::from($data['birthDate']),
            Name::from($data['kingdom']),
            Uuid::from($data['equipmentId']),
            Uuid::from($data['factionId'])
        );
    }
}
