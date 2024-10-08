<?php

namespace App\Tests\Functional\Character;

use App\DataFixtures\AppFixtures;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Symfony\Component\HttpFoundation\Response;

class ReadCharacterTest extends CharacterTestBase
{
    public function testReadCharacterSuccessfully(): void
    {
        $client = self::createClient();
        $characterUuid = UuidMother::withId(AppFixtures::CHARACTER_ID);

        $client->request(
            method: 'GET',
            uri: self::CHARACTER_URI . '/' . $characterUuid->id(),
            server: $this->getAdminAuth()
        );

        $this->assertResponseIsSuccessful();
        $responseData = $this->getResponseData($client);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($characterUuid->id(), $responseData['id']);
        $this->assertValidResponseKeys($responseData);
    }

    public function testReadCharacterForbidden(): void
    {
        $client = self::createClient();
        $characterUuid = UuidMother::withId(AppFixtures::CHARACTER_ID);

        $client->request(
            method: 'GET',
            uri: self::CHARACTER_URI . '/' . $characterUuid->id(),
            server: $this->getInvalidAuth()
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testReadCharacterNotFound(): void
    {
        $client = self::createClient();
        $characterUuid = UuidMother::random();

        $client->request(
            method: 'GET',
            uri: self::CHARACTER_URI . '/' . $characterUuid->id(),
            server: $this->getAdminAuth()
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testReadCharactersList(): void
    {
        $client = self::createClient();

        $client->request(
            method: 'GET',
            uri: self::CHARACTER_URI,
            server: $this->getAdminAuth()
        );

        $this->assertResponseIsSuccessful();
        $responseData = $this->getResponseData($client);
        $this->assertValidResponseKeysInCollection($responseData);
    }
}
