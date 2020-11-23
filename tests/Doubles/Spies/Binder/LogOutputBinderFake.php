<?php

declare(strict_types=1);

namespace Tests\Doubles\Spies\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use Illuminate\Log\LogManager;
use Symfony\Component\Console\Output\OutputInterface;

class LogOutputBinderFake implements LogOutputBindedInterface
{
    private $lastAttachLogManager = null;

    private $lastAttachOutput = null;

    private $lastDetachEvent = null;

    private $lastDetachLogManager = null;

    public function attach(OutputInterface $output, LogManager $logManager): void
    {
        $this->lastAttachOutput = $output;
        $this->lastAttachLogManager = $logManager;
    }

    public function detach(LogManager $logManager): void
    {
        $this->lastDetachLogManager = $logManager;
    }

    public function getLastAttachLogManager(): ?LogManager
    {
        return $this->lastAttachLogManager;
    }

    public function getLastAttachOutput(): ?OutputInterface
    {
        return $this->lastAttachOutput;
    }

    public function getLastDetachLogManager(): ?LogManager
    {
        return $this->lastDetachLogManager;
    }
}
