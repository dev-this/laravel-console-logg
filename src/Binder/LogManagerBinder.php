<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Log\LogManager;
use Illuminate\Log\Writer;
use Psr\Log\LoggerInterface;

/**
 * Responsible for binding a ConsoleLogger instance with the built-in Laravel Logger, and removing
 *
 * @todo nested artisan command call compatibility
 */
class LogManagerBinder implements LogOutputBindedInterface
{
    /**
     * @var FilterableConsoleLoggerFactoryInterface
     */
    private $consoleLoggerFactory;

    /**
     * @var string|null
     */
    private $defaultDriver = null;

    /**
     * @var bool
     */
    private $isFiltered;

    public function __construct(FilterableConsoleLoggerFactoryInterface $consoleLoggerFactory, Repository $config)
    {
        $this->isFiltered = $config->get('console-logg.filtered') === true;
        $this->consoleLoggerFactory = $consoleLoggerFactory;
    }

    public function attach(CommandStarting $commandEvent, LoggerInterface $logger): void
    {
        if (($logger instanceof LogManager::class) === false) {
            throw new \RuntimeException('No.');
        }

        $this->defaultDriver = $logManager->getDefaultDriver();
        $logManager->setDefaultDriver('console-logg');

        $consoleLogger = $this->consoleLoggerFactory->create($commandEvent->output, $this->isFiltered);

        $logManager->extend(
            'console-logg',
            function () use ($consoleLogger) {
                return $consoleLogger;
            }
        );
    }

    public function detach(CommandFinished $commandFinished, LoggerInterface $logger): void
    {
        if (($logger instanceof LogManager::class) === false) {
            throw new \RuntimeException('No.');
        }

        $logManager->forgetChannel('console-logg');
        $logManager->setDefaultDriver($this->defaultDriver);
    }
}
