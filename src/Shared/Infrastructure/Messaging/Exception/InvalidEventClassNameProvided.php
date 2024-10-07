<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Exception;

use Exception;

use function sprintf;

final class InvalidEventClassNameProvided extends Exception
{
    private const MESSAGE = 'invalid event class name provided: %s';

    public function __construct(string $messageName)
    {
        parent::__construct(sprintf(self::MESSAGE, $messageName));
    }
}
