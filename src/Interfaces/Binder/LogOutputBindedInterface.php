<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Binder;

use Illuminate\Log\LogManager;
use Symfony\Component\Console\Output\OutputInterface;

interface LogOutputBindedInterface
{
    public function attach(OutputInterface $output, LogManager $logManager): void;

    public function detach(LogManager $logManager): void;
}
