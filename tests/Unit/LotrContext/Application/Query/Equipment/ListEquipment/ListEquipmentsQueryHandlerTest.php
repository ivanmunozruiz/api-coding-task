<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Query\Equipment\ListEquipment;

use App\LotrContext\Application\Query\Equipment\ListEquipment\ListEquipmentsQuery;
use App\LotrContext\Application\Query\Equipment\ListEquipment\ListEquipmentsQueryHandler;
use App\LotrContext\Application\Query\Equipment\ListEquipment\ListEquipmentsResponse;
use App\LotrContext\Domain\Exception\Equipment\EquipmentNotFoundException;
use App\LotrContext\Domain\Repository\EquipmentRepository;
use App\LotrContext\Domain\Repository\RedisCacheEquipmentRepository;
use App\LotrContext\Domain\Service\Equipment\SearchEquipmentsByCriteria;
use App\Tests\Unit\LotrContext\Domain\Aggregate\EquipmentMother;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;
use Mockery\MockInterface;

final class ListEquipmentsQueryHandlerTest extends UnitTestCase
{
    private EquipmentRepository|MockInterface $equipmentRepository;
    private RedisCacheEquipmentRepository|MockInterface $redisCacheEquipmentRepository;
    private ListEquipmentsQueryHandler $commandHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->equipmentRepository = $this->customMock(EquipmentRepository::class);
        $this->redisCacheEquipmentRepository = $this->customMock(RedisCacheEquipmentRepository::class);
        $searchEquipmentsByCriteria = new SearchEquipmentsByCriteria(
            $this->equipmentRepository
        );

        $this->commandHandler = new ListEquipmentsQueryHandler(
            $searchEquipmentsByCriteria
        );
    }


    /**
     * @throws AssertionFailedException
     * @throws EquipmentNotFoundException
     */
    public function testListEquipmentsQueryHandler(): void
    {
        $page = random_int(1, 10);
        $limit = random_int(1, 10);
        $command = new ListEquipmentsQuery(
            $page,
            $limit
        );
        $equipment = EquipmentMother::create();

        $this->equipmentRepository
            ->shouldReceive('matching')
            ->andReturn([$equipment])
            ->once();

        $this->equipmentRepository
            ->shouldReceive('count')
            ->andReturn(1)
            ->once();

        $response = $this->commandHandler->__invoke($command);
        $this->assertSame(ListEquipmentsResponse::class, $response::class);
    }
}
