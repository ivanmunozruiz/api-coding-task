<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Command\Equipment\DeleteEquipment;

use App\LotrContext\Application\Command\Equipment\DeleteEquipment\DeleteEquipmentCommand;
use App\LotrContext\Application\Command\Equipment\DeleteEquipment\DeleteEquipmentCommandHandler;
use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Repository\CharacterRepository;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheCharacterRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\LotrContext\Domain\Service\Character\CharacterFinder;
use App\LotrContext\Domain\Service\Equipment\EquipmentEraser;
use App\Tests\Unit\LotrContext\Domain\Aggregate\EquipmentMother;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use App\Tests\Unit\UnitTestCase;
use Mockery\MockInterface;

final class DeleteEquipmentCommandHandlerTest extends UnitTestCase
{
    private EquipmentRepository|MockInterface $equipmentRepository;
    private RedisCacheEquipmentRepository|MockInterface $redisCacheEquipmentRepository;
    private DeleteEquipmentCommandHandler $commandHandler;

    private CharacterRepository|MockInterface $characterRepository;

    private RedisCacheCharacterRepository|MockInterface $characterCacheRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->equipmentRepository = $this->customMock(EquipmentRepository::class);
        $this->redisCacheEquipmentRepository = $this->customMock(RedisCacheEquipmentRepository::class);
        $this->characterRepository = $this->customMock(CharacterRepository::class);
        $this->characterCacheRepository = $this->customMock(RedisCacheCharacterRepository::class);
        $characterFinder = new CharacterFinder(
            $this->characterRepository,
            $this->characterCacheRepository
        );
        $equipmentCreator = new EquipmentEraser(
            $this->equipmentRepository,
            $this->redisCacheEquipmentRepository,
            $characterFinder
        );
        $this->commandHandler = new DeleteEquipmentCommandHandler(
            $equipmentCreator,
            $this->eventBus(),
        );
    }

    public function testDeleteEquipmentCommandHandler(): void
    {
        $uuid = UuidMother::random();
        $command = new DeleteEquipmentCommand(
            $uuid->id()
        );
        $equipment = EquipmentMother::create(
            $uuid,
            NameMother::dummy(),
            NameMother::dummy(),
            NameMother::dummy()
        );

        $this->equipmentRepository
            ->shouldReceive('ofIdOrFail')
            ->with($uuid->id())
            ->once()
            ->andReturn($equipment);

        $this->equipmentRepository
            ->shouldReceive('remove')
            ->with($uuid->id())
            ->once()
            ->andReturn($equipment);

        $this->characterRepository
            ->shouldReceive('findOneBy')
            ->with(['equipmentId' => $uuid->id()])
            ->once()
            ->andReturn(null);

        $this->redisCacheEquipmentRepository
            ->shouldReceive('removeData')
            ->with($uuid->id())
            ->once();

        $this->eventBus()
            ->shouldReceive('publish')
            ->once();

        $this->commandHandler->__invoke($command);
    }

    public function testDeleteEquipmentCommandHandlerThrowNotFoundException(): void
    {
        $uuid = UuidMother::random();
        $command = new DeleteEquipmentCommand(
            $uuid->id()
        );

        $this->equipmentRepository
            ->shouldReceive('ofIdOrFail')
            ->with($uuid->id())
            ->once()
            ->andThrow(EquipmentNotFoundException::from($uuid));

        $this->equipmentRepository
            ->shouldReceive('remove')
            ->with($uuid->id())
            ->never();

        $this->redisCacheEquipmentRepository
            ->shouldReceive('removeData')
            ->with($uuid->id())
            ->never();

        $this->eventBus()
            ->shouldReceive('publish')
            ->never();

        $this->expectException(EquipmentNotFoundException::class);
        $this->commandHandler->__invoke($command);
    }
}
