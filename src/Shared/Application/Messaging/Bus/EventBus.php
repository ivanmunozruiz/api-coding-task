<?php

declare(strict_types=1);

namespace App\Shared\Application\Messaging\Bus;

use App\Shared\Domain\Aggregate\Message;

interface EventBus
{
    public function publish(Message ...$message): void;
}
