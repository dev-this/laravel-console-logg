<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Listener;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;

interface LogManagerResolverListenerInterface
{
    /**
     * Invoked by the resolution of LogManager with the Laravel container
     */
    public function handle(LogManager $logManager, Application $application): void;
}
