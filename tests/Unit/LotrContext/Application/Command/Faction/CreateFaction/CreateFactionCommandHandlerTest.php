<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Command\Faction\CreateFaction;

use App\LotrContext\Application\Command\Faction\CreateFaction\CreateFactionCommand;
use App\LotrContext\Application\Command\Faction\CreateFaction\CreateFactionCommandHandler;
use App\LotrContext\Domain\Repository\FactionRepository;
use App\LotrContext\Domain\Repository\RedisCacheFactionRepository;
use App\LotrContext\Domain\Service\Faction\FactionCreator;
use App\Tests\Unit\LotrContext\Domain\Aggregate\FactionMother;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\StringValueObjectMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use App\Tests\Unit\UnitTestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\Exception;

final class CreateFactionCommandHandlerTest extends UnitTestCase
{
    private FactionRepository|MockInterface $factionRepository;
    private RedisCacheFactionRepository|MockInterface $redisCacheFactionRepository;
    private CreateFactionCommandHandler $commandHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->factionRepository = $this->customMock(FactionRepository::class);
        $this->redisCacheFactionRepository = $this->customMock(RedisCacheFactionRepository::class);
        $factionCreator = new FactionCreator(
            $this->factionRepository,
            $this->redisCacheFactionRepository,
        );
        $this->commandHandler = new CreateFactionCommandHandler(
            $factionCreator,
            $this->eventBus(),
        );
    }
    public function testCreateFactionCommandHandler(): void
    {
        $name = NameMother::create('The Fellowship of the Ring');
        $description = StringValueObjectMother::create('A group of nine individuals from Middle-earth');
        $uuid = UuidMother::random();
        $command = new CreateFactionCommand(
            $uuid->id(),
            $name->value(),
            $description->value()
        );
        $faction = FactionMother::create(
            $uuid,
            $name,
            $description
        );
        $faction->delete();

        $this->factionRepository
            ->shouldReceive('ofId')
            ->with($uuid->id())
            ->andReturnNull()
            ->once();
        $this->factionRepository
            ->shouldReceive('ofNameAndDescription')
            ->with($name->value(), $description->value())
            ->andReturnNull()
            ->once();

        $this->factionRepository
            ->shouldReceive('save')
            ->once();

        $this->eventBus()
            ->shouldReceive('publish')
            ->once();

        $this->redisCacheFactionRepository
            ->shouldReceive('setData')
            ->with($uuid->id(), $faction->jsonSerialize())
            ->once();

        $this->commandHandler->__invoke($command);
    }
}
