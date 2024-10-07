<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\Context\JsonContext;
use Behatch\Context\RestContext;
use Behatch\Json\Json;
use Behatch\Json\JsonInspector;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FriendsOfBehat\SymfonyExtension\Context\Environment\InitializedSymfonyExtensionEnvironment;

abstract class BaseApiContext implements Context
{
    use Matchable;

    /** @var array<string,mixed> */
    protected array $bodyArray = [];

    protected JsonContext $jsonContext;

    protected RestContext $restContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $this->bodyArray = [];
        $environment = $scope->getEnvironment();

        \assert($environment instanceof InitializedSymfonyExtensionEnvironment);

        $jsonContext = $environment->getContext(JsonContext::class);
        $restContext = $environment->getContext(RestContext::class);

        $this->jsonContext = $jsonContext;
        $this->restContext = $restContext;
    }

    /**
     * Checks if the json response has an id field, and it is the same as the
     * provided previously for creating an object (assuming that it was created).
     *
     * @Then response should contain the identifier created
     *
     * @throws \Exception|AssertionFailedException
     */
    public function responseShouldContainsTheIdentifierCreated(): void
    {
        $actual = $this->jsonContext->theJsonNodeShouldExist('id');

        if (isset($this->bodyArray['id']) && $this->bodyArray['id'] !== $actual) {
            /** @var string $id */
            $id = $this->bodyArray['id'];

            throw new \RuntimeException(sprintf('the id created %s is not the one specified before: %s', $actual, $id));
        }

        Assertion::uuid($actual);
    }

    /**
     * Sends an HTTP API request with a body.
     *
     * @Given I send a :method api request to :url with body:
     */
    public function iSendARequestToWithBody(string $method, string $url, ?PyStringNode $body = null): void
    {
        $body ??= new PyStringNode([json_encode($this->bodyArray)], 0);
        $this->restContext->theHeaderIsSetEqualTo('Content-Type', 'application/json');
        $this->restContext->iSendARequestTo($method, $url, $body);
    }

    /**
     * @When /^I send a GET api request to "([^"]*)" with query parameters:$/
     *
     * @throws \JsonException
     */
    public function iSendAGETApiRequestToWithQueryParameters(string $url, PyStringNode $queryParameters): void
    {
        /** @var array<string, mixed> $parameters */
        $parameters = json_decode($queryParameters->getRaw(), true, 512, \JSON_THROW_ON_ERROR);
        $query = http_build_query($parameters);
        $url .= '?' . $query;

        $this->restContext->theHeaderIsSetEqualTo('Content-Type', 'application/json');
        $this->restContext->iSendARequestTo('GET', $url);
    }

    /**
     * @When /^I send a GET api request to "([^"]*)"/
     *
     * @throws \JsonException
     */
    public function iSendAGETRequestTo(string $url): void
    {
        $this->restContext->theHeaderIsSetEqualTo('Content-Type', 'application/json');
        $this->restContext->theHeaderIsSetEqualTo('Accept', 'application/json');
        $this->restContext->iSendARequestTo('GET', $url);
    }

    /**
     * Empties the body to be sent a puts just a valid id.
     *
     * @Given /^a (?:nonexistent|non\-existing|non\ existing) (\w+) in the database$/
     *
     * @throws AssertionFailedException
     */
    public function aNonExistentAggregateInTheDatabase(): void
    {
        $this->bodyArray['id'] = (string) UuidMother::dummy();
    }

    /**
     * Sets the id of the object to be sent.
     *
     * @Given /^uuid ([A-Za-z0-9-]+)$/
     * Using a broad pattern to allow invalid uuids
     */
    public function uuid(string $uuid): void
    {
        $this->bodyArray['id'] = $uuid;
    }

    /**
     * Assuming we received an error compares a field of the response json with the one given.
     *
     * @Then /the error (\w+) should be (.+)$/
     *
     * @throws \Exception
     */
    public function theErrorSomethingShouldBeThis(string $something, string $expected): void
    {
        $field = match ($something) {
            'message' => 'detail',
            default => $something,
        };

        $actual = $this->jsonContext->theJsonNodeShouldExist($field);

        if ($expected !== $actual) {
            throw new \RuntimeException(sprintf('the error %s is %s', $something, $actual));
        }
    }

    /**
     * Checks, that given JSON nodes are equal to givens values.
     *
     * @Then the JSON nodes should be similar to:
     *
     * @throws \Throwable
     */
    public function theJsonNodesShouldBeSimilarTo(TableNode $nodes): void
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldBeEqualTo($node, $text);
        }
    }

    public function getLastResponse(): string
    {
        return $this->jsonContext->getMink()->getSession()->getPage()->getContent();
    }

    /**
     * @Given /^the "(\w+)" are empty$/
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function theFollowingTablesAreEmpty(string $tableName): void
    {
        $connection = $this->entityManager()->getConnection();
        $platform = $connection->getDatabasePlatform();
        $table = $this->matchTable($tableName);
        $connection->executeStatement($platform->getTruncateTableSQL($table, true));
    }

    /** @Given /^the current date is (.*)$/ */
    public function changeDateTo(string $toDate): void
    {
        CarbonImmutable::setTestNow($toDate);
    }

    abstract protected function matchTable(string $tableName): string;

    abstract protected function entityManager(): EntityManagerInterface;

    /** @throws \Throwable */
    private function theJsonNodeShouldBeEqualTo(string|int $node, string|int $text): void
    {
        $json = new Json($this->getLastResponse());
        $jsonInspector = new JsonInspector('javascript');

        $actual = (string) $jsonInspector->evaluate($json, $node);
        $this->matchFieldAgainstCurrent($text, $actual);
    }
}
