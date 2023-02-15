<?php
declare(strict_types=1);

namespace DevThis\ConsoleLogg\LogHandlers;

use Monolog\Handler\HandlerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleApp extends ConsoleLogger implements HandlerInterface
{
    // Undocumented method required by Laravel LogManager
    public function getHandlers(): array
    {
        return [$this];
    }

    public function getProcessors(): array
    {
        return [];
    }

    /**
     * @param array|Monolog\LogRecord $record
     */
    public function isHandling($record): bool
    {
        return true;
    }

    /**
     * @param array|Monolog\LogRecord $record
     */
    public function handle($record): bool
    {
        $recordArray = is_array($record) ? $record : $record->toArray();
        $this->log(strtolower($recordArray['level_name'] ?? ''), $recordArray['message'] ?? '', $recordArray['context'] ?? []);

        return true;
    }

    /**
     * @param array|Monolog\LogRecord $record
     */
    public function handleBatch($records): void
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }

    public function close(): void
    {
    }
}
