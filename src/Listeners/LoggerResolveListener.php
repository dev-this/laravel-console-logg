<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Listeners;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LoggerResolveListenerInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Foundation\Application;
use Psr\Log\LoggerInterface;

class LoggerResolveListener implements LoggerResolveListenerInterface
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
    public function handle(LoggerInterface $logger, Application $application): void
    {
        $this->eventDispatcher->listen(
            CommandStarting::class,
            function (CommandStarting $event) use ($logger) {
                $this->logOutputBinder->attach($event, $logger);
            }
        );

        $this->eventDispatcher->listen(
            CommandFinished::class,
            function (CommandFinished $event) use ($logger) {
                $this->logOutputBinder->detach($event, $logger);
            }
        );
    }
}
