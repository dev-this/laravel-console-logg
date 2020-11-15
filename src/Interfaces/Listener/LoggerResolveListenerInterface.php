<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Listener;

use Illuminate\Contracts\Foundation\Application;
use Psr\Log\LoggerInterface;

interface LoggerResolveListenerInterface
{
    /**
     * Invoked by the resolution of LogManager with the Laravel container
     */
    public function handle(LoggerInterface $logger, Application $application): void;
}
