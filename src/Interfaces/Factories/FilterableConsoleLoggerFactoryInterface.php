<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Factories;

use DevThis\ConsoleLogg\Interfaces\Console\FilterableConsoleLoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface FilterableConsoleLoggerFactoryInterface
{
    public function create(OutputInterface $output, ?bool $isFiltered = null): FilterableConsoleLoggerInterface;
}
