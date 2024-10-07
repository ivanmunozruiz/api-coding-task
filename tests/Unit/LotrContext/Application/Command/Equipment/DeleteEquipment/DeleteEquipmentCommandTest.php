<?php

namespace App\Tests\Unit\LotrContext\Application\Command\Equipment\DeleteEquipment;

use App\LotrContext\Application\Command\Equipment\DeleteEquipment\DeleteEquipmentCommand;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;

class DeleteEquipmentCommandTest extends UnitTestCase
{
    /**
     * @dataProvider invalidDataProvider
     */
    public function testDeleteEquipmentCommandHandlerWrongInputThrowAssertionException(
        string $id,
        string $message
    ): void {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage($message);
        new DeleteEquipmentCommand(
            $id,
        );
    }

    public static function invalidDataProvider(): array
    {
        return [
            'empty_id' => [
                'id' => '',
                'message' => 'id is required',
            ]
        ];
    }
}
