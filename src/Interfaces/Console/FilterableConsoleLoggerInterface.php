<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Console;

use Psr\Log\LoggerInterface;

interface FilterableConsoleLoggerInterface extends LoggerInterface
{
    /**
     * Sets the state for whether log filtering (by context) is enabled
     * 'logg' => true should
     */
    public function setFiltered(bool $isFiltered): self;
}
