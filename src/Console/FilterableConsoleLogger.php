<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Console;

use Symfony\Component\Console\Logger\ConsoleLogger;

class FilterableConsoleLogger extends ConsoleLogger
{
    /**
     * @var bool
     */
    private $isFiltered = false;

    public function log($level, $message, array $context = []): void
    {
        if ($this->isFiltered === true && ($context['logg'] ?? false) === true) {
            return;
        }

        parent::log($level, $message, $context);
    }

    public function setFiltered(bool $isFiltered): self
    {
        $this->isFiltered = $isFiltered;

        return $this;
    }
}
