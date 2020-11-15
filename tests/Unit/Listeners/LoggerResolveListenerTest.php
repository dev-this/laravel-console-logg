<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use DevThis\ConsoleLogg\Binder\LogOutputBinder;
use DevThis\ConsoleLogg\Listeners\LoggerResolveListener;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Log\LogManager;
use Illuminate\Log\Writer;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Doubles\Fakes\vendor\Illuminate\ApplicationFake;
use Tests\Doubles\Fakes\vendor\Illuminate\EventDispatcherFake;
use Tests\Doubles\Spies\Binder\LogOutputBinderFake;
use Tests\Doubles\Spies\Factories\FilterableConsoleLoggerFactorySpy;
use Tests\Doubles\Stubs\vendor\Illuminate\LogManagerStub;
use Tests\Doubles\Stubs\vendor\Illuminate\RepositoryStub;

/**
 * @covers \DevThis\ConsoleLogg\Listeners\LoggerResolveListener
 */
class LoggerResolveListenerTest extends TestCase
{
    public function getInputsForEventDispatchListeningTo(): iterable
    {
        yield 'CommandStarting' => [CommandStarting::class];
        yield 'CommandFinished' => [CommandFinished::class];
    }

    public function getInputsForEventListeners(): iterable
    {
        if (class_exists(LogManager::class) === true) {
            yield 'log manager' => ['logger' => new LogManager(new ApplicationFake())];
        }

        if (class_exists(Writer::class) === true) {
            yield 'log writer' => ['logger' => new Writer(new Logger('logger'), new EventDispatcherFake())];
        }
    }

    /**
     * Slightly weak test
     * Testing the spy doesn't prove with certainty
     *
     * @dataProvider getInputsForEventDispatchListeningTo
     */
    public function testEventDispatcherListensTo(string $eventClass): void
    {
        $eventDispatcher = new EventDispatcherFake();
        $filterableConsoleFactory = new FilterableConsoleLoggerFactorySpy();
        $config = new RepositoryStub();
        $logOutputBinder = new LogOutputBinder($filterableConsoleFactory, $config);
        $logManagerResolverListener = new LoggerResolveListener($eventDispatcher, $logOutputBinder);
        $expectedListenersBefore = false;
        $expectedListenersAfter = true;
        $listenersBefore = $eventDispatcher->hasListeners($eventClass);
        $app = new ApplicationFake();
        $logManager = new LogManager($app);

        $logManagerResolverListener->handle($logManager, $app);
        $listenersAfter = $eventDispatcher->hasListeners($eventClass);

        self::assertSame($expectedListenersBefore, $listenersBefore, 'Listener was not expected, but was present');
        self::assertSame($expectedListenersAfter, $listenersAfter, 'Listener was expected, but none found');
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
        $logManagerResolverListener = new LoggerResolveListener($eventDispatcher, $logOutputBinder);
        $app = new ApplicationFake();
        $logManager = new LogManager($app);
        $logManagerResolverListener->handle($logManager, $app);
        $event = new CommandStarting('some:command', new StringInput(''), new NullOutput());
        $actualBeforeEvent = $logOutputBinder->getLastAttachEvent();
        $expectedBeforeEvent = null;
        $expectedAfterEvent = $event;
        // @todo stubs with Input/Output interface
        $closure = $eventDispatcher->getListener(CommandStarting::class);
        $closure($event, new LogManagerStub());

        self::assertSame($expectedAfterEvent, $logOutputBinder->getLastAttachEvent());
        self::assertSame($expectedBeforeEvent, $actualBeforeEvent);
    }

    /**
     * Ensures the 'detach' is called on the LogOutputBinder, and the rightful event is passed through
     *
     * @dataProvider getInputsForEventListeners
     *
     * @todo stub Input/Output interfaces & CommandFinished
     */
    public function testEventListenerDetachesLogManager(LoggerInterface $logger): void
    {
        $eventDispatcher = new EventDispatcherFake();
        $logOutputBinder = new LogOutputBinderFake();
        $logManagerResolverListener = new LoggerResolveListener($eventDispatcher, $logOutputBinder);
        $app = new ApplicationFake();
        $logManagerResolverListener->handle($logger, $app);
        $event = new CommandFinished('some:command', new StringInput(''), new NullOutput(), 1);
        $actualBeforeEvent = $logOutputBinder->getLastDetachEvent();
        $expectedBeforeEvent = null;
        $expectedAfterEvent = $event;
        $closure = $eventDispatcher->getListener(CommandFinished::class);
        $closure($event, $logger);

        self::assertSame($expectedAfterEvent, $logOutputBinder->getLastDetachEvent());
        self::assertSame($expectedBeforeEvent, $actualBeforeEvent);
    }
}
