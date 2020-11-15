<?php

declare(strict_types=1);

namespace Tests\Doubles\Fakes\vendor\Illuminate;

use ArrayObject;
use Closure;
use Illuminate\Contracts\Foundation\Application;

class ApplicationFake extends ArrayObject implements Application
{
    public function __construct(?array $array = null)
    {
        parent::__construct($array);
    }

    public function addContextualBinding($concrete, $abstract, $implementation)
    {
    }

    public function afterResolving($abstract, Closure $callback = null)
    {
    }

    public function alias($abstract, $alias)
    {
    }

    public function basePath($path = '')
    {
    }

    public function bind($abstract, $concrete = null, $shared = false)
    {
    }

    public function bindIf($abstract, $concrete = null, $shared = false)
    {
    }

    public function boot()
    {
    }

    public function booted($callback)
    {
    }

    public function booting($callback)
    {
    }

    public function bootstrapPath($path = '')
    {
    }

    public function bootstrapWith(array $bootstrappers)
    {
    }

    public function bound($abstract)
    {
    }

    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
    }

    public function configPath($path = '')
    {
    }

    public function configurationIsCached()
    {
    }

    public function databasePath($path = '')
    {
    }

    public function detectEnvironment(Closure $callback)
    {
    }

    public function environment(...$environments)
    {
    }

    public function environmentFile()
    {
    }

    public function environmentPath()
    {
    }

    public function extend($abstract, Closure $closure)
    {
    }

    public function factory($abstract)
    {
    }

    public function flush()
    {
    }

    public function get($id)
    {
    }

    public function getLocale()
    {
    }

    public function getNamespace()
    {
    }

    public function getProviders($provider)
    {
    }

    public function has($id)
    {
    }

    public function hasBeenBootstrapped()
    {
    }

    public function instance($abstract, $instance)
    {
    }

    public function isDownForMaintenance()
    {
    }

    public function loadDeferredProviders()
    {
    }

    public function make($abstract, array $parameters = [])
    {
    }

    public function register($provider, $force = false)
    {
    }

    public function registerConfiguredProviders()
    {
    }

    public function registerDeferredProvider($provider, $service = null)
    {
    }

    public function resolveProvider($provider)
    {
    }

    public function resolved($abstract)
    {
    }

    public function resolving($abstract, Closure $callback = null)
    {
    }

    public function resourcePath($path = '')
    {
    }

    public function runningInConsole()
    {
    }

    public function runningUnitTests()
    {
    }

    public function setLocale($locale)
    {
    }

    public function shouldSkipMiddleware()
    {
    }

    public function singleton($abstract, $concrete = null)
    {
    }

    public function singletonIf($abstract, $concrete = null)
    {
    }

    public function storagePath()
    {
    }

    public function tag($abstracts, $tags)
    {
    }

    public function tagged($tag)
    {
    }

    public function terminate()
    {
    }

    public function version()
    {
    }

    public function when($concrete)
    {
    }
}
