<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Exception;

abstract class DomainException extends Exception
{
    public function detail(): string
    {
        return $this->getMessage();
    }

    public function code(): string
    {
        return str_replace(
            '_exception',
            '',
            ClassFunctions::toSnakeCase(ClassFunctions::extractClassName($this))
        );
    }
}
