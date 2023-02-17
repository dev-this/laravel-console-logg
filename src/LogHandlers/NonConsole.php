<?php
declare(strict_types=1);

namespace DevThis\ConsoleLogg\LogHandlers;

use Monolog\Handler\HandlerInterface;
use Psr\Log\NullLogger;

// NonConsole does as little as possible
// it will be resolved as the console-logg log handler driver if the context is not console. (e.g. PHP-FPM)
class NonConsole extends NullLogger implements HandlerInterface {
    // Undocumented method required by Laravel LogManager
    public function getHandlers(): array
    {
        return [];
    }

    /**
     * @param array|Monolog\LogRecord $record
     */
    public function isHandling($record): bool
    {
        return false;
    }

    public function getProcessors(): array
    {
        return [];
    }

    /**
     * @param array|Monolog\LogRecord $record
     */
    public function handle($record): bool
    {
        return false;
    }

    /**
     * @param array|Monolog\LogRecord $record
     */
    public function handleBatch($records): void
    {
    }

    public function close(): void
    {
    }
}
