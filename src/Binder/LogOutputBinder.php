<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\LogHandlers\ConsoleApp;
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
        $consoleLogger = new ConsoleApp($output);

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
