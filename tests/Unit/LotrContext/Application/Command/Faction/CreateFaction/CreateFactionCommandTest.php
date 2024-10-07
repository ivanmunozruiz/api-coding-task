<?php

namespace App\Tests\Unit\LotrContext\Application\Command\Faction\CreateFaction;

use App\LotrContext\Application\Command\Faction\CreateFaction\CreateFactionCommand;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;

class CreateFactionCommandTest extends UnitTestCase
{
    /**
     * @param string $id
     * @param string $name
     * @param string $description
     * @param string $message
     * @return void
     * @dataProvider invalidDataProvider
     * @throws AssertionFailedException
     */
    public function testCreateFactionCommandHandlerEmptyId(
        string $id,
        string $name,
        string $description,
        string $message
    ): void {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage($message);
        $command = new CreateFactionCommand(
            $id,
            $name,
            $description
        );
    }

    public static function invalidDataProvider(): array
    {
        return [
            'empty_id' => [
                'id' => '',
                'name' => 'The Fellowship of the Ring',
                'description' => 'A group of nine individuals',
                'message' => 'id is required',
            ],
            'empty_name' => [
                'id' => '123e4567-e89b-12d3-a456-426614174000',
                'name' => '',
                'description' => 'A group of nine individuals',
                'message' => 'name field is required',
            ],
            'empty_description' => [
                'id' => '123e4567-e89b-12d3-a456-426614174000',
                'name' => 'The Fellowship of the Ring',
                'description' => '',
                'message' => 'description field is required',
            ]
        ];
    }
}
