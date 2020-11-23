<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Console;

use DevThis\ConsoleLogg\Interfaces\Console\ConsoleLoggerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger as SymfonyConsoleLogger;

class ConsoleLogger extends SymfonyConsoleLogger implements ConsoleLoggerInterface
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
    public function setFiltering(bool $isFiltered): ConsoleLoggerInterface
    {
        $this->filtering = $isFiltered;

        return $this;
    }
}
