<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Providers;

use Closure;
use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Factories\FilterableConsoleLoggerFactory;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
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
                __DIR__ . '/../../config/console-logg.php' => $this->app->configPath('console-logg.php')
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
        $this->app->singleton(LogManagerResolverListenerInterface::class, LogManagerResolverListener::class);
        $this->app->singleton(FilterableConsoleLoggerFactoryInterface::class, FilterableConsoleLoggerFactory::class);

        // sorry for this hack :(
        $this->app['config']['logging.channels.console-logg'] = ['driver' => 'console-logg'];

        $this->app->resolving(
            LogManager::class,
            Closure::fromCallable(
                [
                    $this->app->make(LogManagerResolverListenerInterface::class),
                    'handle'
                ]
            )
        );
    }
}
