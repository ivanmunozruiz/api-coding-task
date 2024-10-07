<?php

declare(strict_types=1);

namespace App\LotrContext\Domain\Repository;

use App\LotrContext\Domain\Aggregate\Character;
use App\Shared\Domain\Repository\DomainRepository;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @template-extends DomainRepository<Character>
 */
interface CharacterRepository extends DomainRepository
{
    public function remove(Uuid $identifier): void;

    /**
     * @return Character|null
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?Character;
}
