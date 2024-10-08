<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * @property $container
 */
abstract class ApiTestCase extends WebTestCase
{
    private const CONTENT_TYPE = 'application/json';
    private const ADMIN_API_KEY = 'apiKey';

    private array $collectionKeys = [
        "results",
        "meta"
    ];

    private array $metaKeys = [
        "current_page",
        "last_page",
        "size",
        "total",
    ];

    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadFixtures();
    }

    protected function getFixturesExecutor(): ORMExecutor
    {
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $purger = new ORMPurger($entityManager);

        return new ORMExecutor($entityManager, $purger);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }

    protected function getAdminAuth(
        string $contentType = self::CONTENT_TYPE
    ): array {
        return [
            'CONTENT_TYPE' => $contentType,
            'HTTP_X-AUTH-TOKEN' => self::ADMIN_API_KEY,
        ];
    }

    protected function getInvalidAuth(
        string $contentType = self::CONTENT_TYPE
    ): array {
        return [
            'CONTENT_TYPE' => $contentType,
            'HTTP_X-AUTH-TOKEN' => 'wrongApiKey',
        ];
    }

    protected function getResponseData(KernelBrowser $client): array
    {
        return json_decode($client->getResponse()->getContent(), true);
    }

    protected function assertValidListsResponseKeys(array $list): void
    {
        foreach ($this->collectionKeys as $key) {
            $this->assertArrayHasKey($key, $list);
        }

        foreach ($this->metaKeys as $key) {
            $this->assertArrayHasKey($key, $list['meta']);
        }
    }
}
