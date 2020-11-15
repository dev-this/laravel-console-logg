<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Factories;

use DevThis\ConsoleLogg\Console\FilterableConsoleLogger;
use DevThis\ConsoleLogg\Interfaces\Console\FilterableConsoleLoggerInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilterableConsoleLoggerFactory implements FilterableConsoleLoggerFactoryInterface
{
    public function create(OutputInterface $output, ?bool $isFiltered = null): FilterableConsoleLoggerInterface
    {
        return (new FilterableConsoleLogger($output))
            ->setFiltering($isFiltered ?? false);
    }
}
