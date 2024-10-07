<?php

namespace App\Tests\Unit\LotrContext\Application\Command\Equipment\UpdateEquipment;

use App\LotrContext\Application\Command\Equipment\UpdateEquipment\UpdateEquipmentCommand;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;

class UpdateEquipmentCommandTest extends UnitTestCase
{
    /**
     * @dataProvider invalidDataProvider
     *
     * @throws AssertionFailedException
     */
    public function testUpdateEquipmentCommandHandlerWrongInputThrowAssertionException(
        string $id,
        string $name,
        string $type,
        string $madeBy,
        string $message,
    ): void {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage($message);
        new UpdateEquipmentCommand(
            $id,
            $name,
            $type,
            $madeBy
        );
    }

    public static function invalidDataProvider(): array
    {
        return [
            'empty_id' => [
                'id' => '',
                'name' => 'The Onw Ring',
                'type' => 'Ring',
                'madeBy' => 'Sauron',
                'message' => 'id is required',
            ],
            'empty_name' => [
                'id' => '123e4567-e89b-12d3-a456-426614174000',
                'name' => '',
                'type' => 'Ring',
                'madeBy' => 'Sauron',
                'message' => 'name field is required',
            ],
            'empty_type' => [
                'id' => '123e4567-e89b-12d3-a456-426614174000',
                'name' => 'The One Ring',
                'type' => '',
                'madeBy' => 'Sauron',
                'message' => 'type field is required',
            ],
            'empty_madeBy' => [
                'id' => '123e4567-e89b-12d3-a456-426614174000',
                'name' => 'The One Ring',
                'type' => 'Ring',
                'madeBy' => '',
                'message' => 'madeBy field is required',
            ],
        ];
    }
}
