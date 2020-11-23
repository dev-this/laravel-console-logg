<?php

declare(strict_types=1);

namespace Tests\Unit\Binder;

use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use Illuminate\Log\LogManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;
use Tests\Doubles\Spies\Factories\FilterableConsoleLoggerFactorySpy;
use Tests\Doubles\Spies\vendor\Illuminate\LogManagerSpy;
use Tests\Doubles\Stubs\vendor\Illuminate\RepositoryStub;

/**
 * @covers \DevThis\ConsoleLogg\Binder\LogOutputBinder
 */
class LogOutputBinderTest extends TestCase
{
    public function testAttachSetsDefaultDriver(): void
    {
        $config = new RepositoryStub();
        $filterableConsoleLoggerFactory = new FilterableConsoleLoggerFactorySpy();
        $logOutputBinder = new LogOutputBinder($filterableConsoleLoggerFactory, $config);
        $output = new NullOutput();
        $app = new ApplicationFake(['config' => ['logging.default' => ['driver' => 'the-default']]]);
        $logManager = new LogManager($app);
        $expectation = 'console-logg';

        $logOutputBinder->attach($output, $logManager);

        self::assertSame($expectation, $logManager->getDefaultDriver());
    }

    public function testAttachedConsoleLoggerRespectsFilteredOption(): void
    {
        $config = new RepositoryStub();
        $filterableConsoleLoggerFactory = new FilterableConsoleLoggerFactorySpy();
        $logOutputBinder = new LogOutputBinder($filterableConsoleLoggerFactory, $config);
        $output = new NullOutput();
        $defaultDriver = ['driver' => 'the-default'];
        $app = new ApplicationFake(['config' => ['logging.default' => $defaultDriver]]);
        $logManager = new LogManagerSpy($app);

        $logOutputBinder->attach($output, $logManager);

        self::assertEquals(
            $filterableConsoleLoggerFactory->getLastCreated(),
            $logManager->getCustomCreator('console-logg')()
        );
    }

    public function testDefaultDriverAfterDetachIsNotConsoleLogg(): void
    {
        $config = new RepositoryStub();
        $filterableConsoleLoggerFactory = new FilterableConsoleLoggerFactorySpy();
        $logOutputBinder = new LogOutputBinder($filterableConsoleLoggerFactory, $config);
        $output = new NullOutput();
        $defaultDriver = ['driver' => 'the-default'];
        $app = new ApplicationFake(['config' => ['logging.default' => $defaultDriver]]);
        $logManager = new LogManager($app);

        $logOutputBinder->attach($output, $logManager);
        $logOutputBinder->detach($logManager);

        self::assertSame($defaultDriver, $logManager->getDefaultDriver());
    }

    public function testDefaultDriverWithoutAttachIsNotConsoleLogg(): void
    {
        $app = new ApplicationFake(['config' => ['logging.default' => ['driver' => 'the-default']]]);
        $logManager = new LogManager($app);
        $expectation = 'console-logg';

        self::assertNotSame($expectation, $logManager->getDefaultDriver());
    }
}
