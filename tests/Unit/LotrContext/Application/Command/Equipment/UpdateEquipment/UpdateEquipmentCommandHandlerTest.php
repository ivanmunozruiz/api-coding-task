<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Command\Equipment\UpdateEquipment;

use App\LotrContext\Application\Command\Equipment\UpdateEquipment\UpdateEquipmentCommand;
use App\LotrContext\Application\Command\Equipment\UpdateEquipment\UpdateEquipmentCommandHandler;
use App\LotrContext\Domain\Exception\Equipment\EquipmentAlreadyExistsException;
use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\LotrContext\Domain\Service\Equipment\EquipmentUpdater;
use App\Tests\Unit\LotrContext\Domain\Aggregate\EquipmentMother;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\Exception;

final class UpdateEquipmentCommandHandlerTest extends UnitTestCase
{
    private EquipmentRepository|MockInterface $equipmentRepository;
    private RedisCacheEquipmentRepository|MockInterface $redisCacheEquipmentRepository;
    private UpdateEquipmentCommandHandler $commandHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->equipmentRepository = $this->customMock(EquipmentRepository::class);
        $this->redisCacheEquipmentRepository = $this->customMock(RedisCacheEquipmentRepository::class);
        $equipmentUpdater = new EquipmentUpdater(
            $this->equipmentRepository,
            $this->redisCacheEquipmentRepository,
        );
        $this->commandHandler = new UpdateEquipmentCommandHandler(
            $equipmentUpdater,
            $this->eventBus(),
        );
    }

    /**
     * @throws EquipmentAlreadyExistsException
     * @throws AssertionFailedException
     */
    public function testUpdateEquipmentCommandHandler(): void
    {
        $name = NameMother::create('The One Ring');
        $type = NameMother::create('Ring');
        $madeBy = NameMother::create('Sauron');
        $uuid = UuidMother::random();
        $command = new UpdateEquipmentCommand(
            $uuid->id(),
            $name->value(),
            $type->value(),
            $madeBy->value()
        );
        $equipment = EquipmentMother::create(
            $uuid,
            $name,
            $type,
            $madeBy
        );

        $this->equipmentRepository
            ->shouldReceive('ofIdOrFail')
            ->with($uuid->id())
            ->andReturn($equipment)
            ->once();
        $this->equipmentRepository
            ->shouldReceive('ofNameTypeAndMadeBy')
            ->with($name->value(), $type->value(), $madeBy->value())
            ->andReturnNull()
            ->once();

        $this->equipmentRepository
            ->shouldReceive('save')
            ->once();

        $this->eventBus()
            ->shouldReceive('publish')
            ->once();

        $this->redisCacheEquipmentRepository
            ->shouldReceive('removeData')
            ->with($uuid->id())
            ->once();

        $this->redisCacheEquipmentRepository
            ->shouldReceive('setData')
            ->with($uuid->id(), $equipment->jsonSerialize())
            ->once();

        $this->commandHandler->__invoke($command);
    }

    /**
     * @throws EquipmentAlreadyExistsException
     * @throws AssertionFailedException
     */
    public function testUpdateEquipmentCommandHandlerThrowsEquipmentNotFoundException(): void
    {
        $name = NameMother::create('The One Ring');
        $type = NameMother::create('Ring');
        $madeBy = NameMother::create('Sauron');
        $uuid = UuidMother::random();
        $command = new UpdateEquipmentCommand(
            $uuid->id(),
            $name->value(),
            $type->value(),
            $madeBy->value()
        );
        $equipment = EquipmentMother::create(
            $uuid,
            $name,
            $type,
            $madeBy
        );

        $this->equipmentRepository
            ->shouldReceive('ofIdOrFail')
            ->with($uuid->id())
            ->andThrow(EquipmentNotFoundException::from($uuid))
            ->once();
        $this->equipmentRepository
            ->shouldReceive('ofNameTypeAndMadeBy')
            ->with($name->value(), $type->value(), $madeBy->value())
            ->andReturn($equipment)
            ->never();

        $this->expectException(EquipmentNotFoundException::class);

        $this->commandHandler->__invoke($command);
    }
}
