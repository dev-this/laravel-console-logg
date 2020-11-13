<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Providers;

use Closure;
use DevThis\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Listeners\LogManagerResolverListener;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;

class ConsoleLoggServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../../config/package.php' => config_path('console-logg.php')
            ],
            'config'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        if ($this->app->runningInConsole() === false) {
            return;
        }

        $this->app->singleton(LogOutputBindedInterface::class, LogOutputBinder::class);
        $this->app->singleton(LogManagerResolverListener::class);
        $this->app->singleton(LogOutputBinder::class);

        // sorry for this hack :(
        $this->app['config']['logging.channels.console-logger'] = ['driver' => 'console-logger'];

        $this->app->resolving(
            LogManager::class,
            Closure::fromCallable(
                [
                    $this->app->make(LogManagerResolverListener::class, 'resolve')
                ]
            )
        );
    }
}
