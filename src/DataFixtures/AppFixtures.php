<?php

namespace App\DataFixtures;

use App\Tests\Unit\LotrContext\Domain\Aggregate\CharacterMother;
use App\Tests\Unit\LotrContext\Domain\Aggregate\EquipmentMother;
use App\Tests\Unit\LotrContext\Domain\Aggregate\FactionMother;
use App\Tests\Unit\Shared\Domain\ValueObject\UuidMother;
use Assert\AssertionFailedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const CHARACTER_ID = '0256c68d-7471-4c41-8a32-fc6f733dd003';

    public const FACTION_ID = '5b468353-ec54-4e94-82ee-864714b3e32a';

    public const EQUIPMENT_ID = '0256c68d-7471-4c41-8a32-fc6f733dd005';

    /**
     * @throws AssertionFailedException
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadFactions($manager);
        $this->loadEquipments($manager);
        $this->loadCharacter($manager);
    }

    /**
     * @throws AssertionFailedException
     */
    private function loadFactions(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $factionId = UuidMother::random();
            if ($i === 0) {
                $factionId = UuidMother::withId(self::FACTION_ID);
            }
            $faction = FactionMother::create($factionId);
            $manager->persist($faction);
        }
        $manager->flush();
    }

    /**
     * @throws AssertionFailedException
     */
    private function loadEquipments(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $equipmentId = UuidMother::random();
            if ($i === 0) {
                $equipmentId = UuidMother::withId(self::EQUIPMENT_ID);
            }
            $equipment = EquipmentMother::create($equipmentId);
            $manager->persist($equipment);
        }
        $manager->flush();
    }

    /**
     * @throws AssertionFailedException
     */
    private function loadCharacter(ObjectManager $manager): void
    {
        $character = CharacterMother::create(
            id: UuidMother::withId(self::CHARACTER_ID),
            factionId: UuidMother::withId(self::FACTION_ID),
            equipmentId: UuidMother::withId(self::EQUIPMENT_ID),
        );
        $manager->persist($character);
        $manager->flush();
    }
}
