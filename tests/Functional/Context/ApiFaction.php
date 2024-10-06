<?php

declare(strict_types=1);

namespace App\Tests\Functional\Context;

use App\LotrContext\Domain\Aggregate\Faction;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Behat\Gherkin\Node\TableNode;
use DateTimeInterface;
use Exception;
use App\Shared\Domain\ValueObject\Uuid;

use function is_array;
use function strval;

trait ApiFaction
{
    use ApiFactionDataSeeding;
    use Matchable;

    /** @Then /^the faction with id "(.*)" should exist$/
     * @throws Exception
     */
    public function theFactionWithIdShouldExist(string $id): void
    {
        $this->checkFactionExists($id, true);
    }

    /** @Given /^the faction with id "(.*)" should not exist$/ */
    public function theFactionWithIdShouldNotExist(string $id): void
    {
        $this->checkFactionExists($id, false);
    }

    /** @Then /^the faction should have the fields:$/ */
    public function theFactionShouldHaveField(TableNode $table): void
    {
        // Cache clear is needed to avoid having old data instead of the updated one
        $this->entityManager->clear();

        foreach ($table->getHash() as $row) {
            $faction = $this->factionRepository->ofId(Uuid::from($row['id']));

            if (null === $faction) {
                continue;
            }

            $this->checkObjectAgainstExpected($faction, $row);
        }
    }

    /** @Then /^the faction should have non-empty fields:$/ */
    public function theFactionShouldHaveNonEmptyField(TableNode $table): void
    {
        // Cache clear is needed to avoid having old data instead of the updated one
        $this->entityManager->clear();

        foreach ($table->getHash() as $row) {
            $faction = $this->factionRepository->ofId(Uuid::from($row['id']));

            if (null === $faction) {
                continue;
            }

            $this->checkObjectPropertiesAreFilled($faction, (string) $row['fields']);
        }
    }

    private function checkFactionExists(string $id, bool $shouldExist): void
    {
        // Cache clear is needed to avoid having old data instead of the updated one
        $this->entityManager->clear();

        $faction = $this->factionRepository->ofId(Uuid::from($id));

        if ($shouldExist === (!$faction instanceof Faction)) {
            throw new Exception('Condition failed for :' . $id);
        }
    }

    /** @throws Exception */
    private function checkObjectPropertiesAreFilled(Faction $objectToCheck, string $fields): void
    {
        $factionArr = $objectToCheck->jsonSerialize();

        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            $field = trim($field);

            /** @phpstan-ignore-next-line */
            if (null === $factionArr[$field]) {
                throw new Exception(sprintf('Field %s was not empty as expected', $field));
            }
        }
    }

    /**
     * @throws Exception
     * @throws AssertionFailedException
     * @phpstan-ignore-next-line
     */
    private function checkArrayAgainstExpected(array $dataArray, array $row): void
    {
        foreach ($row as $field => $value) {
            $value = 'null' === $value ? null : $value;

            $currentValue = is_array($dataArray[$field])
                ? strval(json_encode($dataArray[$field]))
                : strval(
                    $dataArray[$field],
                );

            $this->matchFieldAgainstCurrent($value, $currentValue);
        }
    }

    /**
     * @param array<string, mixed> $row
     */
    private function checkObjectAgainstExpected(Faction $objectToCheck, array $row): void
    {
        $dataArray = $objectToCheck->jsonSerialize();

        foreach ($row as $field => $value) {
            $this->checkFieldValue($value, $dataArray[$field]);
        }
    }

    /** @phpstan-param array<string, mixed>|bool|string|null $currentValue */
    private function checkFieldValue(mixed $expectedValue, mixed $currentValue): void
    {
        $expectedValue = 'null' === $expectedValue ? null : $expectedValue;

        $currentValue = is_array($currentValue)
            ? json_encode($currentValue)
            : strval(
                $currentValue,
            );

        match ($expectedValue) {
            default => Assertion::eq($expectedValue, $currentValue)
        };
    }
}
