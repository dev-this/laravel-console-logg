<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Binder;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;

interface LogOutputBindedInterface
{
    public function attach(CommandStarting $commandEvent, LoggerInterface $logger): void;

    public function detach(CommandFinished $commandFinished, LoggerInterface $logger): void;
}
