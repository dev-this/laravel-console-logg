<?php

declare(strict_types=1);

namespace Tests\Doubles\Spies\Factories;

use DevThis\ConsoleLogg\Factories\ConsoleLoggerFactory;
use DevThis\ConsoleLogg\Interfaces\Console\ConsoleLoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleLoggerFactorySpy extends ConsoleLoggerFactory
{
    /**
     * @var ConsoleLoggerInterface|null
     */
    private $lastCreated;

    /**
     * @var OutputInterface
     */
    private $lastOutput;

    public function create(OutputInterface $output): ConsoleLoggerInterface
    {
        $this->lastOutput = $output;

        return $this->lastCreated = parent::create($output);
    }

    public function createFilterable(OutputInterface $output): ConsoleLoggerInterface
    {
        $this->lastOutput = $output;

        return $this->lastCreated = parent::createFilterable($output);
    }

    public function getLastCreated(): ?ConsoleLoggerInterface
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
