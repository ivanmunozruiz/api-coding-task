<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Query\Equipment\FetchEquipment;

use App\LotrContext\Application\Query\Equipment\FetchEquipment\FetchEquipmentQuery;
use App\LotrContext\Application\Query\Equipment\FetchEquipment\FetchEquipmentQueryHandler;
use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\LotrContext\Domain\Service\Equipment\EquipmentFinder;
use App\Tests\Unit\LotrContext\Domain\Aggregate\EquipmentMother;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;
use Mockery\MockInterface;

final class FetchEquipmentQueryHandlerTest extends UnitTestCase
{
    private EquipmentRepository|MockInterface $equipmentRepository;
    private RedisCacheEquipmentRepository|MockInterface $redisCacheEquipmentRepository;
    private FetchEquipmentQueryHandler $commandHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->equipmentRepository = $this->customMock(EquipmentRepository::class);
        $this->redisCacheEquipmentRepository = $this->customMock(RedisCacheEquipmentRepository::class);
        $equipmentFinder = new EquipmentFinder(
            $this->equipmentRepository,
            $this->redisCacheEquipmentRepository,
        );
        $this->commandHandler = new FetchEquipmentQueryHandler(
            $equipmentFinder
        );
    }

    /**
     * @throws AssertionFailedException
     * @throws EquipmentNotFoundException
     */
    public function testFetchEquipmentQueryHandlerFromDatabase(): void
    {
        $name = NameMother::create('The One Ring');
        $type = NameMother::create('Ring');
        $madeBy = NameMother::create('Sauron');
        $uuid = UuidMother::random();
        $command = new FetchEquipmentQuery(
            $uuid->id()
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

        $this->redisCacheEquipmentRepository
            ->shouldReceive('getData')
            ->with($uuid->id())
            ->once();

        $this->commandHandler->__invoke($command);
    }

    /**
     * @throws AssertionFailedException
     * @throws EquipmentNotFoundException
     */
    public function testFetchEquipmentQueryHandlerFromCache(): void
    {
        $name = NameMother::create('The One Ring');
        $type = NameMother::create('Ring');
        $madeBy = NameMother::create('Sauron');
        $uuid = UuidMother::random();
        $command = new FetchEquipmentQuery(
            $uuid->id()
        );

        $this->redisCacheEquipmentRepository
            ->shouldReceive('getData')
            ->with($uuid->id())
            ->andReturn([
                'id' => $uuid->id(),
                'name' => $name->value(),
                'type' => $type->value(),
                'made_by' => $madeBy->value(),
            ])
            ->once();

        $this->equipmentRepository
            ->shouldReceive('ofIdOrFail')
            ->never();

        $this->commandHandler->__invoke($command);
    }
}
