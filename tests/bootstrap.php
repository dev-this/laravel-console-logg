<?php

declare(strict_types=1);

use Illuminate\Contracts\Foundation\Application;

require_once(__DIR__ . '/../vendor/autoload.php');

// We need to try and work out what version of Laravel we're working with
$r = new ReflectionClass(Application::class);

if (count($r->getMethod('register')->getParameters()) === 3) {
    // 5.*
    putenv('L_VERSION=5x');

    return;
}

putenv('L_VERSION=>6x');
