<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
use DevThis\ConsoleLogg\Listeners\LogManagerResolverListener;
use DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;

/**
 * @covers \DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider
 */
class ConsoleLoggServiceProviderTest extends TestCase
{
    public function testRegisterBindings(): void
    {
        $app = new ApplicationFake();
        $serviceProvider = new ConsoleLoggServiceProvider($app);

        $serviceProvider->register();

        self::assertSame(LogOutputBinder::class, $app->get(LogOutputBindedInterface::class));
        self::assertSame(LogManagerResolverListener::class, $app->get(LogManagerResolverListenerInterface::class));
    }

    public function testRegisterDoesNothingForNonConsole(): void
    {
        $app = new ApplicationFake(null, false);
        $serviceProvider = new ConsoleLoggServiceProvider($app);

        $serviceProvider->register();

        self::assertNotSame(LogOutputBinder::class, $app->get(LogOutputBindedInterface::class));
        self::assertNotSame(LogManagerResolverListener::class, $app->get(LogManagerResolverListenerInterface::class));
        self::assertNotSame(
            FilterableConsoleLoggerFactory::class,
            $app->get(FilterableConsoleLoggerFactoryInterface::class)
        );
    }

    public function testRegisterSetsFakeLoggingChannel(): void
    {
        $app = new ApplicationFake();
        $serviceProvider = new ConsoleLoggServiceProvider($app);
        $expectationBefore = null;
        $expectationAfter = ['logging.channels.console-logg' => ['driver' => 'console-logg']];
        $actualBefore = $app['config'] ?? null;
        $serviceProvider->register();

        $result = $app['config'] ?? null;

        self::assertSame($expectationAfter, $result);
        self::assertSame($expectationBefore, $actualBefore);
    }
}
