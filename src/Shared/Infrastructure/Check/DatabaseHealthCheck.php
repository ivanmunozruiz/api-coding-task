<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Throwable;

final class DatabaseHealthCheck implements HealthCheck
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function name(): string
    {
        return 'database';
    }

    public function status(): string
    {
        try {
            $entityManager = $this->container->get('doctrine.orm.entity_manager');
            Assertion::isInstanceOf($entityManager, EntityManager::class);
            $con = $entityManager->getConnection();
            $con->executeQuery($con->getDatabasePlatform()->getDummySelectSQL())->free();
        } catch (Throwable) {
            return Status::FAIL->value;
        }

        return Status::SUCCESS->value;
    }
}
