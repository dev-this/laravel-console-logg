<?php

declare(strict_types=1);

namespace Tests\Doubles\Fakes\vendor\Illuminate;

use ArrayObject;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
use DevThis\ConsoleLogg\Listeners\LoggerResolveListener;
use Illuminate\Contracts\Foundation\Application;
use Tests\Doubles\Spies\Binder\LogOutputBinderFake;

abstract class ApplicationFakeBase extends ArrayObject implements Application
{
    use ApplicationSharedMethods;

    private $isConsole;

    private $registered = [];

    public function __construct(?array $array = null, ?bool $isConsole = null)
    {
        parent::__construct($array ?? []);
        $this->isConsole = $isConsole ?? true;
    }

    public function configPath($path = '')
    {
        return '%APP%/' . $path;
    }

    public function get($id)
    {
        return $this->registered[$id] ?? $id;
    }

    public function make($abstract, array $parameters = [])
    {
        if ($abstract === LogManagerResolverListenerInterface::class) {
            return new LoggerResolveListener(new EventDispatcherFake(), new LogOutputBinderFake());
        }
    }

    public function runningInConsole()
    {
        return $this->isConsole;
    }

    public function singleton($abstract, $concrete = null)
    {
        $this->registered[$abstract] = $concrete ?? $abstract;
    }
}
