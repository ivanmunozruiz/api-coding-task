<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Equipment\FetchEquipment;

use App\LotrContext\Domain\Aggregate\Equipment;
use App\Shared\Application\Query\QueryResponse;

final class FetchEquipmentQueryResponse implements QueryResponse
{
    private function __construct(private readonly Equipment $equipment)
    {
    }

    public static function write(Equipment $equipment): self
    {
        return new self($equipment);
    }

    /** @return array{
     *     id: string,
     *     name: string,
     *     type: string,
     *     made_by: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->equipment->jsonSerialize();
    }
}
