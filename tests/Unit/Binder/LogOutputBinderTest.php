<?php

declare(strict_types=1);

namespace Tests\Unit\Binder;

use Illuminate\Log\LogManager;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;

/**
 * @covers \DevThis\ConsoleLogg\Binder\LogOutputBinder
 */
class LogOutputBinderTest extends TestCase
{
    public function testDefaultDriverWithoutAttachIsNotConsoleLogg(): void
    {
        $app = new ApplicationFake(['config' => ['logging.default' => ['driver' => 'the-default']]]);
        $logManager = new LogManager($app);
        $expectation = 'console-logg';

        self::assertNotSame($expectation, $logManager->getDefaultDriver());
    }
}
