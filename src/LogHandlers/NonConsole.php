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

    public function isHandling(array $record): bool
    {
        return false;
    }

    public function handle(array $record): bool
    {
        return false;
    }

    public function handleBatch(array $records): void
    {
    }

    public function close(): void
    {
    }
}
