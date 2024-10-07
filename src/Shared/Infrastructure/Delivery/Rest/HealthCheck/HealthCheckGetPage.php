<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest\HealthCheck;

use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Infrastructure\Check\HealthCheck;
use Doctrine\Migrations\DependencyFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class HealthCheckGetPage
{
    /** @param array<HealthCheck> $healthChecks */
    public function __construct(
        private readonly array $healthChecks,
        private readonly DependencyFactory $dependencyFactory,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $resultHealthCheck = [
            'time_zone' => date_default_timezone_get(),
            'updated_at' => DateTimeValueObject::now()->toRfc3339String(),
        ];
        foreach ($this->healthChecks as $healthCheck) {
            $resultHealthCheck[$healthCheck->name()] = $healthCheck->status();
        }
        $infosHelper = $this->dependencyFactory->getMigrationStatusCalculator();
        $resultHealthCheck['migrations'] = $infosHelper->getNewMigrations()->count() > 0 ? 'KO' : 'OK';
        return new JsonResponse(
            $resultHealthCheck,
        );
    }
}
