<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
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

    public function attach(OutputInterface $output, LogManager $logManager): void
    {
        $this->defaultDriver = $logManager->getDefaultDriver();
        $logManager->setDefaultDriver('console-logg');

        $consoleLogger = $this->consoleLoggerFactory->create($output, $this->isFiltered);

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
}
