<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use DevThis\ConsoleLogg\Listeners\LogManagerResolverListener;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Log\LogManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;
use Tests\Doubles\Fakes\vendor\Illuminate\EventDispatcherFake;
use Tests\Doubles\Spies\Binder\LogOutputBinderFake;
use Tests\Doubles\Stubs\vendor\Illuminate\LogManagerStub;

/**
 * @covers \DevThis\ConsoleLogg\Listeners\LogManagerResolverListener
 */
class LogManagerResolverListenerTest extends TestCase
{
    public function getInputsForEventDispatchListeningTo(): iterable
    {
        yield 'CommandStarting' => [CommandStarting::class];
        yield 'CommandFinished' => [CommandFinished::class];
    }

    public function testCliServerDetection(): void
    {
        $eventDispatcher = new EventDispatcherFake();
        $logOutputBinder = new LogOutputBinderFake();
        $logManagerResolverListener = new class($eventDispatcher, $logOutputBinder) extends LogManagerResolverListener {
            protected function isCliServer(): bool
            {
                return true;
            }
        };
        $app = new ApplicationFake();
        $logManager = new LogManager($app);
        $logManagerResolverListener->handle($logManager, $app);

        self::assertInstanceOf(StreamOutput::class, $logOutputBinder->getLastAttachOutput());
    }

    public function testCliServerSkips(): void
    {
        $eventDispatcher = new EventDispatcherFake();
        $logOutputBinder = new LogOutputBinderFake();
        $logManagerResolverListener = new LogManagerResolverListener($eventDispatcher, $logOutputBinder);
        $app = new ApplicationFake();
        $logManager = new LogManager($app);
        $logManagerResolverListener->handle($logManager, $app);

        self::assertNotInstanceOf(StreamOutput::class, $logOutputBinder->getLastAttachOutput());
    }

    /**
     * Ensures the 'attach' is called on the LogOutputBinder, and the rightful event is passed through
     *
     * @todo stub Input/Output interfaces & CommandFinished
     */
    public function testEventListenerAttachesLogManager(): void
    {
        $eventDispatcher = new EventDispatcherFake();
        $logOutputBinder = new LogOutputBinderFake();
        $logManagerResolverListener = new LogManagerResolverListener($eventDispatcher, $logOutputBinder);
        $app = new ApplicationFake();
        $logManager = new LogManager($app);
        $logManagerResolverListener->handle($logManager, $app);
        $output = new NullOutput();
        $event = new CommandStarting('some:command', new StringInput(''), $output);
        // @todo stubs with Input/Output interface
        $closure = $eventDispatcher->getListener(CommandStarting::class);
        $closure($event, new LogManagerStub());

        self::assertSame($output, $logOutputBinder->getLastAttachOutput());
    }

    /**
     * Ensures the 'detach' is called on the LogOutputBinder, and the rightful event is passed through
     *
     * @todo stub Input/Output interfaces & CommandFinished
     */
    public function testEventListenerDetachesLogManager(): void
    {
        $eventDispatcher = new EventDispatcherFake();
        $logOutputBinder = new LogOutputBinderFake();
        $logManagerResolverListener = new LogManagerResolverListener($eventDispatcher, $logOutputBinder);
        $app = new ApplicationFake();
        $logManager = new LogManager($app);
        $logManagerResolverListener->handle($logManager, $app);
        $event = new CommandFinished('some:command', new StringInput(''), new NullOutput(), 1);
        $actualBeforeEvent = $logOutputBinder->getLastDetachLogManager();
        $expectedBeforeEvent = null;
        $expectedAfterEvent = $logManager;
        $closure = $eventDispatcher->getListener(CommandFinished::class);
        $closure($event, $logManager);

        self::assertSame($expectedBeforeEvent, $actualBeforeEvent);
        self::assertSame($expectedAfterEvent, $logOutputBinder->getLastDetachLogManager());
    }
}
