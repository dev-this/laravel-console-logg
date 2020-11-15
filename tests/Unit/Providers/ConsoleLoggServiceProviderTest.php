<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

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
        $s = new ConsoleLoggServiceProvider($app);
        $expectedPublishedPathsBefore = [];
        $actualPublishedPathsBefore = ConsoleLoggServiceProvider::pathsToPublish();
        $expectedPublishedPaths = [
            sprintf(
                '%s%s',
                dirname($reflection->getFileName()),
                '/../../config/console-logg.php'
            ) => '%APP%/' . 'console-logg.php'
        ];

        $s->boot();
        $publishedPaths = ConsoleLoggServiceProvider::pathsToPublish();

        self::assertSame($expectedPublishedPaths, $publishedPaths);
        self::assertSame($expectedPublishedPathsBefore, $actualPublishedPathsBefore);
    }
}
