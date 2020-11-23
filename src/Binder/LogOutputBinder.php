<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Console\ConsoleLoggerInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\ConsoleLoggerFactoryInterface;
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
    /**
     * @var ConsoleLoggerFactoryInterface
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

    public function __construct(ConsoleLoggerFactoryInterface $consoleLoggerFactory, Repository $config)
    {
        $this->isFiltered = $config->get('console-logg.filtered') === true;
        $this->consoleLoggerFactory = $consoleLoggerFactory;
    }

    public function attach(OutputInterface $output, LogManager $logManager): void
    {
        $this->defaultDriver = $logManager->getDefaultDriver();
        $logManager->setDefaultDriver('console-logg');

        $consoleLogger = $this->createConsoleLogger($output);

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
        $logManager->setDefaultDriver($this->defaultDriver);
    }

    private function createConsoleLogger(OutputInterface $output): ConsoleLoggerInterface
    {
        if ($this->isFiltered === true) {
            return $this->consoleLoggerFactory->createFilterable($output);
        }

        return $this->consoleLoggerFactory->create($output);
    }
}
