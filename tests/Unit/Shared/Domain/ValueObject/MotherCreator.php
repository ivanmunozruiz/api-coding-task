<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\ValueObject;

use Faker\Factory;
use Faker\Generator;

/**
 * @see https://martinfowler.com/bliki/ObjectMother.html
 * @see https://en.wikipedia.org/wiki/Lazy_loading
 */
final class MotherCreator
{
    private static Generator $generator;

    public static function generate(): Generator
    {
        self::$generator ??= Factory::create();

        return self::$generator;
    }

    public static function repeater(string $char, int $maxChars): string
    {
        return str_repeat($char, random_int(0, $maxChars));
    }
}
