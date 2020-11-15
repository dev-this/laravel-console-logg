<?php

declare(strict_types=1);

namespace Tests\Doubles\Spies\Binder;

use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Psr\Log\LoggerInterface;

class LogOutputBinderFake implements LogOutputBindedInterface
{
    private $lastAttachEvent = null;

    private $lastAttachLogManager = null;

    private $lastDetachEvent = null;

    private $lastDetachLogManager = null;

    public function attach(CommandStarting $commandEvent, LoggerInterface $logger): void
    {
        $this->lastAttachEvent = $commandEvent;
        $this->lastAttachLogManager = $logger;
    }

    public function detach(CommandFinished $commandFinished, LoggerInterface $logger): void
    {
        $this->lastDetachEvent = $commandFinished;
        $this->lastDetachLogManager = $logger;
    }

    public function getLastAttachEvent(): ?CommandStarting
    {
        return $this->lastAttachEvent;
    }

    public function getLastAttachLogManager(): ?LoggerInterface
    {
        return $this->lastAttachLogManager;
    }

    public function getLastDetachEvent(): ?CommandFinished
    {
        return $this->lastDetachEvent;
    }

    public function getLastDetachLogManager(): ?LoggerInterface
    {
        return $this->lastDetachLogManager;
    }
}
