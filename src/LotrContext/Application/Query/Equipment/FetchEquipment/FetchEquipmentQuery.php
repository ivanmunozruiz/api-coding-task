<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Query\Equipment\FetchEquipment;

use App\Shared\Application\Query\Query;
use Assert\Assertion;

final class FetchEquipmentQuery implements Query
{
    public function __construct(private readonly string $identifier)
    {
        Assertion::notEmpty($identifier, 'id is required');
    }

    public function identifier(): string
    {
        return $this->identifier;
    }
}
