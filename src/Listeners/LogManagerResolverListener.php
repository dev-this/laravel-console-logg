<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Listeners;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;
use Symfony\Component\Console\Output\StreamOutput;

class LogManagerResolverListener implements LogManagerResolverListenerInterface
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    private $eventDispatcher;

    /**
     * @var \DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface
     */
    private $logOutputBinder;

    public function __construct(EventDispatcher $eventDispatcher, LogOutputBindedInterface $logOutputBinder)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->logOutputBinder = $logOutputBinder;
    }

    /**
     * @inheritDoc
     */
    public function handle(LogManager $logManager, Application $application): void
    {
        if ($this->isCliServer() === true) {
            // Parent process forwards standard output
            $output = new StreamOutput(\fopen('php://stdout', 'wb'));
            $this->logOutputBinder->attach($output, $logManager);

            return;
        }

        $this->eventDispatcher->listen(
            CommandStarting::class,
            function (CommandStarting $event) use ($logManager) {
                $this->logOutputBinder->attach($event->output, $logManager);
            }
        );

        $this->eventDispatcher->listen(
            CommandFinished::class,
            function (CommandFinished $event) use ($logManager) {
                $this->logOutputBinder->detach($logManager);
            }
        );
    }

    protected function isCliServer(): bool
    {
        return PHP_SAPI === 'cli-server';
    }
}
