<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Providers;

use Closure;
use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Factories\FilterableConsoleLoggerFactory;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LoggerResolveListenerInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
use DevThis\ConsoleLogg\Listeners\LoggerResolveListener;
use DevThis\ConsoleLogg\Listeners\LogWriterResolverListener;
use Illuminate\Log\LogManager;
use Illuminate\Log\Writer;
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
        $this->app->singleton(LoggerResolveListenerInterface::class, LoggerResolveListener::class);
        $this->app->singleton(FilterableConsoleLoggerFactoryInterface::class, FilterableConsoleLoggerFactory::class);

        // sorry for this hack :(
        $this->app['config']['logging.channels.console-logg'] = ['driver' => 'console-logg'];

        $logger = (class_exists(LogManager::class) === true) ? LogManager::class : Writer::class;

        $this->app->resolving(
            $logger,
            Closure::fromCallable(
                [
                    $this->app->make(LoggerResolveListenerInterface::class),
                    'handle'
                ]
            )
        );
    }
}
