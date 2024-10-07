<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Equipment\UpdateEquipment;

use Assert\Assertion;
use App\Shared\Application\Command\Command;

final class UpdateEquipmentCommand implements Command
{
    public function __construct(
        private readonly string $identifier,
        private readonly string $name,
        private readonly string $type,
        private readonly string $madeBy,
    ) {
        Assertion::notEmpty($identifier, 'The id is required');
        Assertion::notEmpty($name, 'name field is required');
        Assertion::notEmpty($type, 'type field is required');
        Assertion::notEmpty($madeBy, 'madeBy field is required');
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function madeBy(): string
    {
        return $this->madeBy;
    }
}
