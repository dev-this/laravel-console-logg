<?php

declare(strict_types=1);

namespace Tests\Unit\Binder;

use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Log\LogManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
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
        $stringInput = new StringInput('some:command');
        $commandStarting = new CommandStarting('Some\Command', $stringInput, new NullOutput());
        $app = new ApplicationFake(['config' => ['logging.default' => ['driver' => 'the-default']]]);
        $logManager = new LogManager($app);
        $expectation = 'console-logg';

        $logOutputBinder->attach($commandStarting, $logManager);

        self::assertSame($expectation, $logManager->getDefaultDriver());
    }

    public function testAttachedConsoleLoggerRespectsFilteredOption(): void
    {
        $config = new RepositoryStub();
        $filterableConsoleLoggerFactory = new FilterableConsoleLoggerFactorySpy();
        $logOutputBinder = new LogOutputBinder($filterableConsoleLoggerFactory, $config);
        $stringInput = new StringInput('some:command');
        $output = new NullOutput();
        $commandStarting = new CommandStarting('Some\Command', $stringInput, $output);
        $defaultDriver = ['driver' => 'the-default'];
        $app = new ApplicationFake(['config' => ['logging.default' => $defaultDriver]]);
        $logManager = new LogManagerSpy($app);

        $logOutputBinder->attach($commandStarting, $logManager);

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
        $stringInput = new StringInput('some:command');
        $commandStarting = new CommandStarting('Some\Command', $stringInput, new NullOutput());
        $commandFinished = new CommandFinished('Some\Command', $stringInput, new NullOutput(), 0);
        $defaultDriver = ['driver' => 'the-default'];
        $app = new ApplicationFake(['config' => ['logging.default' => $defaultDriver]]);
        $logManager = new LogManager($app);

        $logOutputBinder->attach($commandStarting, $logManager);
        $logOutputBinder->detach($commandFinished, $logManager);

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
