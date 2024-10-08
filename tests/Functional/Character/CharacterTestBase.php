<?php

declare(strict_types=1);

namespace App\Tests\Functional\Character;

use App\Tests\Functional\ApiTestCase;

class CharacterTestBase extends ApiTestCase
{
    protected const CHARACTER_URI = '/api/v1/characters';

    protected array $keys = [
        'id',
        'name',
        'birth_date',
        'kingdom',
        'faction_id',
        'equipment_id',
    ];

    protected function assertValidResponseKeys(array $responseData): void
    {
        foreach ($this->keys as $key) {
            $this->assertArrayHasKey($key, $responseData);
        }
    }

    protected function assertValidResponseKeysInCollection(array $responseData): void
    {
        $this->assertValidListsResponseKeys($responseData);
        $results = $responseData['results'] ?? [];
        foreach ($results as $response) {
            $this->assertValidResponseKeys($response);
        }
    }
}
