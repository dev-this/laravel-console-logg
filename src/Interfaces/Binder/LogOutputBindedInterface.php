<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Binder;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Log\LogManager;

interface LogOutputBindedInterface
{
    public function attach(CommandStarting $commandEvent, LogManager $logManager): void;

    public function detach(CommandFinished $commandFinished, LogManager $logManager): void;
}
