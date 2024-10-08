<?php

declare(strict_types=1);

namespace App\Tests\Unit\LotrContext\Application\Query\Equipment\ListEquipment;

use App\LotrContext\Application\Query\Equipment\ListEquipment\ListEquipmentsQuery;
use App\Tests\Unit\UnitTestCase;
use Assert\AssertionFailedException;

final class ListEquipmentsQueryTest extends UnitTestCase
{
    /**
     * @dataProvider invalidDataProvider
     */
    public function testListEquipmentsQueryHandlerWrongInputThrowAssertionException(
        int $page,
        int $size,
        string $message,
    ): void {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage($message);
        new ListEquipmentsQuery(
            $page,
            $size,
        );
    }

    public static function invalidDataProvider(): array
    {
        return [
            'page_less_than_1' => [
                'page' => 0,
                'size' => 10,
                'message' => 'Page should be greater than or equal to 1',
            ],
            'size_less_than_1' => [
                'page' => 1,
                'size' => 0,
                'message' => 'Size should be greater than or equal to 1',
            ],
        ];
    }
}
