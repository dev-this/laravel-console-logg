<?php

declare(strict_types=1);

namespace Tests\Doubles\Fakes\vendor\Illuminate;

use Illuminate\Contracts\Events\Dispatcher;

class EventDispatcherFake implements Dispatcher
{
    private $listeners = [];

    public function dispatch($event, $payload = [], $halt = false)
    {
    }

    public function flush($event)
    {
    }

    public function forget($event)
    {
    }

    public function forgetPushed()
    {
    }

    public function getListener($event)
    {
        return $this->listeners[$event] ?? null;
    }

    public function hasListeners($eventName)
    {
        return array_key_exists($eventName, $this->listeners);
    }

    public function listen($events, $listener = null)
    {
        if (is_array($events) === false) {
            $events = [$events];
        }

        foreach ($events as $event) {
            $this->listeners[$event] = $listener;
        }
    }

    public function push($event, $payload = [])
    {
    }

    public function subscribe($subscriber)
    {
    }

    public function until($event, $payload = [])
    {
    }
}
