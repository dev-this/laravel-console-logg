<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Factories;

use DevThis\ConsoleLogg\Console\ConsoleLogger;
use DevThis\ConsoleLogg\Interfaces\Console\ConsoleLoggerInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\ConsoleLoggerFactoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleLoggerFactory implements ConsoleLoggerFactoryInterface
{
    public function create(OutputInterface $output): ConsoleLoggerInterface
    {
        return new ConsoleLogger($output);
    }

    public function createFilterable(OutputInterface $output): ConsoleLoggerInterface
    {
        return (new ConsoleLogger($output))
            ->setFiltering(true);
    }
}
