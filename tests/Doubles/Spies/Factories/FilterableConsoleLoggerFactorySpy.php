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

    /**
     * @vars bool|null
     */
    private $lastIsFiltered;

    /**
     * @var OutputInterface
     */
    private $lastOutput;

    public function create(OutputInterface $output, ?bool $isFiltered = null): FilterableConsoleLoggerInterface
    {
        $this->lastIsFiltered = $isFiltered;
        $this->lastOutput = $output;

        return $this->lastCreated = parent::create($output, $isFiltered);
    }

    public function getLastCreated(): ?FilterableConsoleLoggerInterface
    {
        return $this->lastCreated;
    }

    public function getLastIsFilteredValue(): ?bool
    {
        return $this->lastIsFiltered;
    }

    public function getLastOutput(): OutputInterface
    {
        return $this->lastOutput;
    }
}
