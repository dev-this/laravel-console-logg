<?php

declare(strict_types=1);

namespace Tests\Doubles\Spies\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Log\LogManager;

class LogOutputBinderFake implements LogOutputBindedInterface
{
    private $lastAttachEvent = null;

    private $lastAttachLogManager = null;

    private $lastDetachEvent = null;

    private $lastDetachLogManager = null;

    public function attach(CommandStarting $commandEvent, LogManager $logManager): void
    {
        $this->lastAttachEvent = $commandEvent;
        $this->lastAttachLogManager = $logManager;
    }

    public function detach(CommandFinished $commandFinished, LogManager $logManager): void
    {
        $this->lastDetachEvent = $commandFinished;
        $this->lastDetachLogManager = $logManager;
    }

    public function getLastAttachEvent(): ?CommandStarting
    {
        return $this->lastAttachEvent;
    }

    public function getLastAttachLogManager(): ?LogManager
    {
        return $this->lastAttachLogManager;
    }

    public function getLastDetachEvent(): ?CommandFinished
    {
        return $this->lastDetachEvent;
    }

    public function getLastDetachLogManager(): ?LogManager
    {
        return $this->lastDetachLogManager;
    }
}
