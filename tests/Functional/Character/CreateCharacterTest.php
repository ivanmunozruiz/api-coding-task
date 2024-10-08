<?php

namespace App\Tests\Functional\Character;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\LotrContext\Domain\Aggregate\Faction;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Symfony\Component\HttpFoundation\Response;

class CreateCharacterTest extends CharacterTestBase
{
    public function testCreateCharacterSuccessfully()
    {
        $client = self::createClient();

        $factions = $this->getEntityManager()
            ->getRepository(Faction::class)
            ->findAll();
        $equipments = $this->getEntityManager()
            ->getRepository(Equipment::class)
            ->findAll();

        $factionCount = count($factions);
        $equipmentCount = count($equipments);
        $faction = $factions[rand(0, $factionCount - 1)];
        $equipment = $equipments[rand(0, $equipmentCount - 1)];

        $data = [
            'id' => UuidMother::random()->id(),
            'name' => 'Aragorn',
            'factionId' => $faction->id()->id(),
            'equipmentId' => $equipment->id()->id(),
            'birthDate' => '2956-03-01',
            'kingdom' => 'Gondor 25',
        ];

        $client->request(
            method: 'POST',
            uri: self::CHARACTER_URI,
            server: $this->getAdminAuth(),
            content: json_encode($data)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testCreateCharacterUnprocessable()
    {
        $client = self::createClient();

        $factions = $this->getEntityManager()
            ->getRepository(Faction::class)
            ->findAll();
        $equipments = $this->getEntityManager()
            ->getRepository(Equipment::class)
            ->findAll();

        $factionCount = count($factions);
        $equipmentCount = count($equipments);
        $faction = $factions[rand(0, $factionCount - 1)];
        $equipment = $equipments[rand(0, $equipmentCount - 1)];

        $data = [
            'id' => UuidMother::random()->id(),
            'factionId' => $faction->id()->id(),
            'equipmentId' => $equipment->id()->id(),
            'birthDate' => '2956-03-01',
            'kingdom' => 'Gondor 25',
        ];

        $client->request(
            method: 'POST',
            uri: self::CHARACTER_URI,
            server: $this->getAdminAuth(),
            content: json_encode($data)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateCharacterNotFound()
    {
        $client = self::createClient();

        $data = [
            'id' => UuidMother::random()->id(),
            'name' => 'Aragorn',
            'factionId' => UuidMother::random()->id(),
            'equipmentId' => UuidMother::random()->id(),
            'birthDate' => '2956-03-01',
            'kingdom' => 'Gondor 25',
        ];

        $client->request(
            method: 'POST',
            uri: self::CHARACTER_URI,
            server: $this->getAdminAuth(),
            content: json_encode($data)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreateCharacterForbidden()
    {
        $client = self::createClient();

        $data = [
            'id' => UuidMother::random()->id(),
            'name' => 'Aragorn',
            'factionId' => UuidMother::random()->id(),
            'equipmentId' => UuidMother::random()->id(),
            'birthDate' => '2956-03-01',
            'kingdom' => 'Gondor 25',
        ];

        $client->request(
            method: 'POST',
            uri: self::CHARACTER_URI,
            server: $this->getInvalidAuth(),
            content: json_encode($data)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
