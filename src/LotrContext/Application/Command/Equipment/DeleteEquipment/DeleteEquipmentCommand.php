<?php

declare(strict_types=1);

namespace App\LotrContext\Application\Command\Equipment\DeleteEquipment;

use App\Shared\Application\Command\Command;

final class DeleteEquipmentCommand implements Command
{
    public function __construct(private readonly string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
