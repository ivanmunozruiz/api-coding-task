<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface Equalable
{
    public function isEqualTo(object $other): bool;
}
