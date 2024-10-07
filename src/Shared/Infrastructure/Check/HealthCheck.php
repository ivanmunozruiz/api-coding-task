<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

interface HealthCheck
{
    public function name(): string;

    public function status(): string;
}
