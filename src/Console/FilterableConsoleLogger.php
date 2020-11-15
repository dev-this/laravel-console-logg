<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Console;

use DevThis\ConsoleLogg\Interfaces\Console\FilterableConsoleLoggerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class FilterableConsoleLogger extends ConsoleLogger implements FilterableConsoleLoggerInterface
{
    /**
     * @var bool
     */
    private $filtering = false;

    public function isFiltered(): bool
    {
        return $this->filtering;
    }

    public function log($level, $message, array $context = []): void
    {
        if ($this->filtering === true && ($context['logg'] ?? false) !== true) {
            return;
        }

        parent::log($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function setFiltering(bool $isFiltered): FilterableConsoleLoggerInterface
    {
        $this->filtering = $isFiltered;

        return $this;
    }
}
