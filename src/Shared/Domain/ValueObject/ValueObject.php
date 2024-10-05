<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use JsonSerializable;
use Stringable;
use App\Shared\Domain\Equalable;

interface ValueObject extends JsonSerializable, Equalable, Stringable
{
}
