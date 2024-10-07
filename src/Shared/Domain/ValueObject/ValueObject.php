<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Equalable;
use Stringable;

interface ValueObject extends \JsonSerializable, Equalable, Stringable
{
}
