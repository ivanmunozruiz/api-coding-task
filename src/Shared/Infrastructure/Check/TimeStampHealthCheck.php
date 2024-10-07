<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

use Carbon\CarbonImmutable;

final class TimeStampHealthCheck implements HealthCheck
{
    public function name(): string
    {
        return 'timestamp';
    }

    public function status(): string
    {
        return (string) CarbonImmutable::now()->getTimestamp();
    }
}
