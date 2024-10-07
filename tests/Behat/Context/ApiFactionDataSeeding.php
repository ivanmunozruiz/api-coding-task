<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Unit\LotrContext\Domain\Aggregate\FactionMother;
use App\Tests\Unit\Shared\Domain\ValueObject\NameMother;
use App\Tests\Unit\Shared\Domain\ValueObject\StringValueObjectMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Assert\AssertionFailedException;
use Behat\Gherkin\Node\TableNode;

trait ApiFactionDataSeeding
{
    /**
     * @Given /^the following factions exist:$/
     * @throws AssertionFailedException
     */
    public function theFollowingUserExist(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $user = FactionMother::create(
                UuidMother::create($row['id'] ?? null),
                NameMother::create($row['name'] ?? null),
                StringValueObjectMother::create($row['description'] ?? null)
            );

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
