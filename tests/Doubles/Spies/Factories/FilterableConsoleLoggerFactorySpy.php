<?php

declare(strict_types=1);

namespace Tests\Doubles\Spies\Factories;

use DevThis\ConsoleLogg\Factories\FilterableConsoleLoggerFactory;
use DevThis\ConsoleLogg\Interfaces\Console\FilterableConsoleLoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilterableConsoleLoggerFactorySpy extends FilterableConsoleLoggerFactory
{
    /**
     * @var FilterableConsoleLoggerInterface|null
     */
    private $lastCreated;

    public function create(OutputInterface $output, ?bool $isFiltered = null): FilterableConsoleLoggerInterface
    {
        return $this->lastCreated = parent::create($output, $isFiltered);
    }

    public function getLastCreated(): ?FilterableConsoleLoggerInterface
    {
        return $this->lastCreated;
    }
}
