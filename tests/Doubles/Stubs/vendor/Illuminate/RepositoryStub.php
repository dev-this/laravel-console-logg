<?php

declare(strict_types=1);

namespace Tests\Doubles\Stubs\vendor\Illuminate;

use Illuminate\Contracts\Config\Repository;

class RepositoryStub implements Repository
{
    public function all()
    {
    }

    public function get($key, $default = null)
    {
    }

    public function has($key)
    {
    }

    public function prepend($key, $value)
    {
    }

    public function push($key, $value)
    {
    }

    public function set($key, $value = null)
    {
    }
}
