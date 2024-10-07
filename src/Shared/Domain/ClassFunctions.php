<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class ClassFunctions
{
    public static function extractClassName(object $object): string
    {
        $reflectionClass = new \ReflectionClass(objectOrClass: $object);

        if ($reflectionClass->isAnonymous()) {
            return '';
        }

        return $reflectionClass->getShortName();
    }

    public static function extractClassNameFromString(string $className, string $delimiter = '_'): string
    {
        $classNameSplit = explode('\\', $className);

        $nameSanitized = preg_replace('#[A-Z]([A-Z](?![a-z]))*#', $delimiter . '$0', end($classNameSplit));

        return ltrim(sprintf('%s', $nameSanitized), $delimiter);
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text)
            ? $text
            : strtolower(preg_replace('#([^A-Z\s])([A-Z])#', '$1_$2', $text) ?? '');
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $text))));
    }

    public static function toKebabCase(string $text): string
    {
        return ctype_lower($text)
            ? $text
            : strtolower(preg_replace('#([^A-Z\s])([A-Z])#', '$1-$2', $text) ?? '');
    }
}
