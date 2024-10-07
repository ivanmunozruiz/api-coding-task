<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

use function is_string;

final class EnvironmentHealthCheck implements HealthCheck
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function name(): string
    {
        return 'environment';
    }

    public function status(): string
    {
        $kernelEnvironment = $this->container->getParameter('kernel.environment');

        if (!is_string($kernelEnvironment)) {
            throw new Exception('kernel.environment is not a string');
        }

        return $kernelEnvironment;
    }
}
