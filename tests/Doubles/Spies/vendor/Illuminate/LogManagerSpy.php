<?php

declare(strict_types=1);

namespace Tests\Doubles\Spies\vendor\Illuminate;

use Illuminate\Log\LogManager;

class LogManagerSpy extends LogManager
{
    public function getCustomCreator(string $name)
    {
        return $this->customCreators[$name];
    }
}
