<?php

declare(strict_types=1);

namespace App\Tests;

use function dirname;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
}

(new \Symfony\Component\Dotenv\Dotenv())->bootEnv(dirname(__DIR__) . '/.env.test');
