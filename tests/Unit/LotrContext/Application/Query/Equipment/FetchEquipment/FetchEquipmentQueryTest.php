<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Query\Equipment\FetchEquipment;

use App\LotrContext\Application\Query\Equipment\FetchEquipment\FetchEquipmentQuery;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;

final class FetchEquipmentQueryTest extends UnitTestCase
{
    /**
     * @dataProvider invalidDataProvider
     */
    public function testFetchEquipmentQueryHandlerWrongInputThrowAssertionException(
        string $id,
        string $message,
    ): void {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage($message);
        new FetchEquipmentQuery(
            $id,
        );
    }

    public static function invalidDataProvider(): array
    {
        return [
            'empty_id' => [
                'id' => '',
                'message' => 'id is required',
            ],
        ];
    }
}
