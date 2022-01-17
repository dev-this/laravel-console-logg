<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Console\ConsoleLogg;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Log\LogManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Responsible for binding a ConsoleLogger instance with the built-in Laravel Logger, and removing
 *
 * @todo nested artisan command call compatibility
 */
class LogOutputBinder implements LogOutputBindedInterface
{
    public function attach(OutputInterface $output, LogManager $logManager): void
    {
        $consoleLogger = new ConsoleLogg($output);

        $logManager->extend(
            'console-logg',
            function () use ($consoleLogger) {
                return $consoleLogger;
            }
        );
    }

    public function detach(LogManager $logManager): void
    {
        $logManager->forgetChannel('console-logg');
    }
}
