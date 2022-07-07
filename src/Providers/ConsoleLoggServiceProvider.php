<?php

declare(strict_types=1);

namespace DevThis\ConsoleLogg\Providers;

use Closure;
use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
use DevThis\ConsoleLogg\Listeners\LogManagerResolverListener;
use DevThis\ConsoleLogg\LogHandlers\NonConsole;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;

class ConsoleLoggServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        // TODO: Move this into documentation in a non-breaking fashion.
        $this->app['config']['logging.channels.console-logg'] = ['driver' => 'console-logg'];

        if ($this->app->runningInConsole() === false) {
            $this->app->resolving(
                LogManager::class,
                static function (LogManager $logManager) {
                    $logManager->extend(
                        'console-logg',
                        function () {
                            return new NonConsole();
                        }
                    );
                }
            );

            return;
        }

        $this->app->singleton(LogOutputBindedInterface::class, LogOutputBinder::class);
        $this->app->singleton(LogManagerResolverListenerInterface::class, LogManagerResolverListener::class);

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
