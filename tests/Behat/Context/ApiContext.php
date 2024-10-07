<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\LotrContext\Domain\Repository\FactionRepository;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client as RedisClient;
use RuntimeException;

final class ApiContext extends BaseApiContext
{
    use ApiFaction;
    use AuthorisationContext;

    private const LOTR_TABLES = [
        'lotr.factions',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FactionRepository $factionRepository,
        private readonly RedisClient $redis,
    ) {
    }

    /** @BeforeScenario */
    public function cleanDB(BeforeScenarioScope $scope): void
    {
        $connection = $this->entityManager()->getConnection();
        $platform = $connection->getDatabasePlatform();
        $this->entityManager()->clear();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach (self::LOTR_TABLES as $table) {
            $connection->executeStatement($platform->getTruncateTableSQL($table));
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1;');
        $connection->close();
    }

    /** @BeforeScenario */
    public static function resetDate(BeforeScenarioScope $scope): void
    {
        CarbonImmutable::setTestNow(DateTimeValueObject::now()->datetime());
    }

    /**
     * It does nothing.
     *
     * @Given /^empty data$/
     */
    public function emptyData(): void
    {
        // do nothing
    }

    /**
     * Sends an HTTP request with json content type and a previously created body.
     *
     * @Given I send it in the body of a :method request to :url
     */
    public function iSendARequestTo(string $method, string $url): void
    {
        $pyStringNode = new PyStringNode([json_encode($this->bodyArray)], 0);
        $this->restContext->iSendARequestTo($method, $url, $pyStringNode);
    }

    /**
     * It resets the body array.
     *
     * @Given /^empty body$/
     */
    public function emptyBody(): void
    {
        $this->bodyArray = [];
    }

    /**
     * Set field with some value.
     *
     * @Given the field :field with value :value
     */
    public function setFieldWithValue(string $field, string $value): void
    {
        $this->bodyArray[$field] = $value;
    }

    /**
     * Sets some field of the object to be sent.
     *
     * @Given /^(?:a\ |an\ )([A-Za-z0-9-_]+) field with an empty value$/
     */
    public function setFieldWithEmptyValue(string $field): void
    {
        $this->bodyArray[$field] = '';
    }

    /**
     * Sets some field of the object to be sent.
     *
     * @Given /^no ([A-Za-z0-9-_]+) field in the body$/
     */
    public function unsetFieldFromBody(string $field): void
    {
        unset($this->bodyArray[$field]);
    }

    public function getLastStatusCode(): int
    {
        return $this->restContext->getMink()->getSession()->getStatusCode();
    }

    public function matchTable(string $tableName): string
    {
        return match ($tableName) {
            'users', 'user_access', 'roles' => sprintf('user_context.%s', $tableName),
            'stored_events' => sprintf('shared.%s', $tableName),
            default => throw new RuntimeException(sprintf('Table %s is not configured.', $tableName)),
        };
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function cache(): RedisClient
    {
        return $this->redis;
    }
}
