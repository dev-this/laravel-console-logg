<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Factories\FilterableConsoleLoggerFactory;
use DevThis\ConsoleLogg\Interfaces\Binder\LogOutputBindedInterface;
use DevThis\ConsoleLogg\Interfaces\Factories\FilterableConsoleLoggerFactoryInterface;
use DevThis\ConsoleLogg\Interfaces\Listener\LogManagerResolverListenerInterface;
use DevThis\ConsoleLogg\Listeners\LogManagerResolverListener;
use DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;

/**
 * @covers \DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider
 */
class ConsoleLoggServiceProviderTest extends TestCase
{
    /**
     * Ensure the published
     *
     * Don't copy Reflection in your test at home
     */
    public function testPublishedPath(): void
    {
        $app = new ApplicationFake();
        // if we don't use reflection to determine file location we would need fuzzy asserting
        $reflection = new ReflectionClass(ConsoleLoggServiceProvider::class);
        $serviceProvider = new ConsoleLoggServiceProvider($app);
        $expectedPublishedPathsBefore = [];
        $actualPublishedPathsBefore = ConsoleLoggServiceProvider::pathsToPublish();
        $expectedPublishedPaths = [
            sprintf(
                '%s%s',
                dirname($reflection->getFileName()),
                '/../../config/console-logg.php'
            ) => '%APP%/' . 'console-logg.php'
        ];

        $serviceProvider->boot();
        $publishedPaths = ConsoleLoggServiceProvider::pathsToPublish();

        self::assertSame($expectedPublishedPaths, $publishedPaths);
        self::assertSame($expectedPublishedPathsBefore, $actualPublishedPathsBefore);
    }

    public function testRegisterBindings(): void
    {
        $app = new ApplicationFake();
        $serviceProvider = new ConsoleLoggServiceProvider($app);

        $serviceProvider->register();

        self::assertSame(LogOutputBinder::class, $app->get(LogOutputBindedInterface::class));
        self::assertSame(LogManagerResolverListener::class, $app->get(LogManagerResolverListenerInterface::class));
        self::assertSame(
            FilterableConsoleLoggerFactory::class,
            $app->get(FilterableConsoleLoggerFactoryInterface::class)
        );
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
