<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    // Define paths
    $rectorConfig->paths([__DIR__.'/src']);

    // Define rulesets to apply
    $rectorConfig->sets([
        SetList::PHP_81,  // For PHP 8.1 compatibility
        SetList::CODE_QUALITY, // Code quality improvements
        SetList::DEAD_CODE,    // Remove dead code
    ]);
};
