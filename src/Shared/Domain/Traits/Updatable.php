<?php

declare(strict_types=1);

namespace App\Shared\Domain\Traits;

use App\Shared\Domain\ValueObject\DateTimeValueObject;

trait Updatable
{
    private DateTimeValueObject $updatedAt;

    public function updatedAt(): DateTimeValueObject
    {
        return $this->updatedAt;
    }

    private function updatedAtNow(): void
    {
        $this->updatedAt = DateTimeValueObject::now();
    }
}
