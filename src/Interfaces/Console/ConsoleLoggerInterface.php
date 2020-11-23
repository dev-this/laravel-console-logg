<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Console;

use Psr\Log\LoggerInterface;

interface ConsoleLoggerInterface extends LoggerInterface
{
    public function isFiltered(): bool;

    /**
     * Sets the state for whether log filtering (by context) is enabled
     * 'logg' => true should
     *
     * @return \DevThis\ConsoleLogg\Interfaces\Console\ConsoleLoggerInterface
     */
    public function setFiltering(bool $isFiltered): ConsoleLoggerInterface;
}
