<?php

declare(strict_types=1);

namespace Tests\Doubles\Stubs\vendor\Illuminate;

use Illuminate\Log\LogManager;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;

class LogManagerStub extends LogManager
{
    public function __construct($app = null)
    {
        parent::__construct($app ?? new ApplicationFake());
    }
}

