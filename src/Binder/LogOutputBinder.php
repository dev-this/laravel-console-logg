<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Console\FilterableConsoleLogger;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Log\LogManager;

class LogOutputBinder implements LogOutputBindedInterface
{
    /**
     * @var string|null
     */
    private $defaultDriver = null;

    /**
     * @var bool
     */
    private $isFiltered;

    public function __construct(Repository $config)
    {
        $this->isFiltered = $config->get('console-logg.filtered') === true;
    }

    public function attach(CommandStarting $commandEvent, LogManager $logManager): void
    {
        $this->defaultDriver = $logManager->getDefaultDriver();
        $logManager->setDefaultDriver('console-logg');

        $consoleLogger = (new FilterableConsoleLogger($commandEvent->output))
            ->setFiltered($this->isFiltered);

        $logManager->extend(
            'console-logg',
            function () use ($consoleLogger) {
                return $consoleLogger;
            }
        );
    }

    public function detach(CommandFinished $commandFinished, LogManager $logManager): void
    {
        $logManager->forgetChannel('console-logg');
        $logManager->setDefaultDriver($this->defaultDriver);
    }
}
