<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Command\Equipment\CreateEquipment;

use App\LotrContext\Application\Command\Equipment\CreateEquipment\CreateEquipmentCommand;
use App\LotrContext\Application\Command\Equipment\CreateEquipment\CreateEquipmentCommandHandler;
use App\LotrContext\Domain\Exception\Equipment\EquipmentAlreadyExistsException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\LotrContext\Domain\Service\Equipment\EquipmentCreator;
use App\Tests\Unit\LotrContext\Domain\Aggregate\EquipmentMother;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\Exception;

final class CreateEquipmentCommandHandlerTest extends UnitTestCase
{
    private EquipmentRepository|MockInterface $equipmentRepository;
    private RedisCacheEquipmentRepository|MockInterface $redisCacheEquipmentRepository;
    private CreateEquipmentCommandHandler $commandHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->equipmentRepository = $this->customMock(EquipmentRepository::class);
        $this->redisCacheEquipmentRepository = $this->customMock(RedisCacheEquipmentRepository::class);
        $equipmentCreator = new EquipmentCreator(
            $this->equipmentRepository,
            $this->redisCacheEquipmentRepository,
        );
        $this->commandHandler = new CreateEquipmentCommandHandler(
            $equipmentCreator,
            $this->eventBus(),
        );
    }

    /**
     * @throws EquipmentAlreadyExistsException
     * @throws AssertionFailedException
     */
    public function testCreateEquipmentCommandHandler(): void
    {
        $name = NameMother::create('The One Ring');
        $type = NameMother::create('Ring');
        $madeBy = NameMother::create('Sauron');
        $uuid = UuidMother::random();
        $command = new CreateEquipmentCommand(
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
            ->shouldReceive('ofId')
            ->with($uuid->id())
            ->andReturnNull()
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
            ->shouldReceive('setData')
            ->with($uuid->id(), $equipment->jsonSerialize())
            ->once();

        $this->commandHandler->__invoke($command);
    }

    /**
     * @throws EquipmentAlreadyExistsException
     * @throws AssertionFailedException
     */
    public function testCreateEquipmentCommandHandlerThrowsEquipmentAlreadyExistsException(): void
    {
        $name = NameMother::create('The One Ring');
        $type = NameMother::create('Ring');
        $madeBy = NameMother::create('Sauron');
        $uuid = UuidMother::random();
        $command = new CreateEquipmentCommand(
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
            ->shouldReceive('ofId')
            ->with($uuid->id())
            ->andReturnNull()
            ->once();
        $this->equipmentRepository
            ->shouldReceive('ofNameTypeAndMadeBy')
            ->with($name->value(), $type->value(), $madeBy->value())
            ->andReturn($equipment)
            ->once();

        $this->expectException(EquipmentAlreadyExistsException::class);

        $this->commandHandler->__invoke($command);
    }
}
