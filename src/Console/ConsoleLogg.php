<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Console;

use Monolog\Handler\HandlerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleLogg extends ConsoleLogger implements HandlerInterface
{
    public function getHandlers(): array
    {
        return [$this];
    }

    public function getProcessors(): array
    {
        return [];
    }

    public function isHandling(array $record): bool
    {
        return true;
    }

    public function handle(array $record): bool
    {
        $this->log(strtolower($record['level_name'] ?? ''), $record['message'] ?? '', $record['context'] ?? []);

        return true;
    }

    public function handleBatch(array $records): void
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }

    public function close(): void
    {
    }
}
