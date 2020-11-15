<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use Illuminate\Log\LogManager;
use Illuminate\Log\Writer;

if (class_exists(LogManager::class) === true) {
    class_alias(LogManager::class, LogOutputBinder::class);
}

if (class_exists(Writer::class) === true) {
    class_alias(LogWriterBinder::class, LogOutputBinder::class);
}
