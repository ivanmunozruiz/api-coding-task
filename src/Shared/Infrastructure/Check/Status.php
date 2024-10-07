<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Check;

// phpcs:ignoreFile
enum Status: string
{
    case SUCCESS = 'OK';
    case FAIL = 'KO';
}
