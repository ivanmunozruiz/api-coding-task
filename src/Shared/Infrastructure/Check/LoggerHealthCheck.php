<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

use Psr\Log\LoggerInterface;

final class LoggerHealthCheck implements HealthCheck
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function name(): string
    {
        return 'logger';
    }

    public function status(): string
    {
        try {
            $this->logger->info('Checking if logs are writable');

            return Status::SUCCESS->value;
        } catch (\Throwable) {
            return Status::FAIL->value;
        }
    }
}
