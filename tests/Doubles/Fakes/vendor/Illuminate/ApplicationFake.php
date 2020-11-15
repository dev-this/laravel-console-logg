<?php

declare(strict_types=1);

namespace Tests\Doubles\Fakes\vendor\Illuminate;

if (getenv('L_VERSION') === '5x') {
    class ApplicationFake extends ApplicationFakeBase
    {
        public function register($provider, $options = [], $force = false)
        {
        }
    }

    return;
}

class ApplicationFake extends ApplicationFakeBase
{
    public function register($provider, $force = false)
    {
    }
}
