<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Listeners;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;

class LogManagerResolverListener
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var \DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface
     */
    private $logOutputBinder;

    public function __construct(
        EventDispatcher $eventDispatcher,
        LogOutputBindedInterface $logOutputBinder
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->logOutputBinder = $logOutputBinder;
    }

    public function resolve(LogManager $logManager, Application $application): void
    {
        $this->eventDispatcher->listen(
            CommandStarting::class,
            function (CommandStarting $event) use ($logManager) {
                $this->logOutputBinder->attach($event, $logManager);
            }
        );

        $this->eventDispatcher->listen(
            CommandFinished::class,
            function (CommandFinished $event) use ($logManager) {
                $this->logOutputBinder->detach($event, $logManager);
            }
        );
    }
}
