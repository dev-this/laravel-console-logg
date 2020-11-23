<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Interfaces\Factories;

use DevThis\ConsoleLogg\Interfaces\Console\ConsoleLoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleLoggerFactoryInterface
{
    public function create(OutputInterface $output): ConsoleLoggerInterface;

    public function createFilterable(OutputInterface $output): ConsoleLoggerInterface;
}
