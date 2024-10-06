<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface Encoder
{
    /** @param array<string, mixed> $data */
    public function encode(array $data): string;

    public function decode(string $data): mixed;
}
