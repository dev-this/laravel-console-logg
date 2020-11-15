<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Log\Writer;
use Monolog\Handler\PsrHandler;
use Psr\Log\LoggerInterface;

class LogWriterBinder implements LogOutputBindedInterface
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
        if (($logger instanceof Writer) === false) {
            throw new \RuntimeException('No.');
        }

        $consoleLogger = $this->consoleLoggerFactory->create($commandEvent->output, $this->isFiltered);
        $psrHandler = new PsrHandler($consoleLogger);

        if (in_array($psrHandler, $logger->getMonolog()->getHandlers(), true) === false) {
            $logger->getMonolog()->pushHandler($psrHandler);
        }
    }

    public function detach(CommandFinished $commandFinished, LoggerInterface $logger): void
    {
        if (($logger instanceof Writer) === false) {
            throw new \RuntimeException('No.');
        }

        $logger->getMonolog()->popHandler();
    }
}
