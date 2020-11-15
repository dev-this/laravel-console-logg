<?php

declare(strict_types=1);

namespace Tests\Doubles\Fakes\vendor\Illuminate;

use Closure;

trait ApplicationSharedMethods
{
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

    public function environmentFilePath()
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


    public function getCachedConfigPath()
    {
    }

    public function getCachedPackagesPath()
    {
    }

    public function getCachedRoutesPath()
    {
    }

    public function getCachedServicesPath()
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

    public function loadEnvironmentFrom($file)
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

    public function routesAreCached()
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