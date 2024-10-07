<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Equipment\FetchEquipment;

use App\Shared\Application\Query\Query;

final class FetchEquipmentQuery implements Query
{
    public function __construct(private readonly string $identifier)
    {
    }

    public function identifier(): string
    {
        return $this->identifier;
    }
}
