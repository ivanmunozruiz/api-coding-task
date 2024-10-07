<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

final class StatusHealthCheck implements HealthCheck
{
    public function name(): string
    {
        return 'status';
    }

    public function status(): string
    {
        return Status::SUCCESS->value;
    }
}
