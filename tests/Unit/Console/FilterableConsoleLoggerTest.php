<?php

declare(strict_types=1);

namespace Tests\Unit\Console;

use DevThis\ConsoleLogg\Console\FilterableConsoleLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \DevThis\ConsoleLogg\Console\FilterableConsoleLogger
 */
class FilterableConsoleLoggerTest extends TestCase
{
    public function getInputsForFilteredOptionsTest(): iterable
    {
        yield 'Filtering on - One log with context' => [
            'logs' => [
                [LogLevel::INFO, 'Some cool message', ['logg' => true]],
                [LogLevel::DEBUG, 'Please don\'t appear'],
            ],
            'isFiltered' => true,
            'expectation' => <<<expect
[info] Some cool message
expect
        ];

        yield 'Filtering on. All logs contextual' => [
            'logs' => [
                [LogLevel::INFO, 'Some cool message', ['logg' => true]],
                [LogLevel::DEBUG, 'Okay show me your words', ['logg' => true]],
            ],
            'isFiltered' => false,
            'expectation' => <<<expect
[info] Some cool message
[debug] Okay show me your words
expect
        ];

        yield 'No filtering at all' => [
            'logs' => [
                [LogLevel::INFO, 'Some cool message'],
                [LogLevel::DEBUG, 'Okay show me your words'],
            ],
            'isFiltered' => false,
            'expectation' => <<<expect
[info] Some cool message
[debug] Okay show me your words
expect
        ];

        yield 'No filtering at all - with context' => [ // (should have no impact with context)
            'logs' => [
                [LogLevel::INFO, 'Some cool message', ['logg' => false]],
                [LogLevel::DEBUG, 'Okay show me your words'],
            ],
            'isFiltered' => false,
            'expectation' => <<<expect
[info] Some cool message
[debug] Okay show me your words
expect
        ];

        yield 'No filtering at all - with context, but inverse' => [ // (should have no impact with context)
            'logs' => [
                [LogLevel::INFO, 'Some cool message', ['logg' => true]],
                [LogLevel::DEBUG, 'Okay show me your words'],
            ],
            'isFiltered' => false,
            'expectation' => <<<expect
[info] Some cool message
[debug] Okay show me your words
expect
        ];

        yield 'Filtering on. Weird context values' => [ // (context weird values should === false)
            'logs' => [
                [LogLevel::INFO, 'Some cool message', ['logg' => 'maybe']],
                [LogLevel::INFO, 'Some cool message #2', ['logg' => 'maybe']],
                [LogLevel::DEBUG, 'Good one', ['logg' => true]],
            ],
            'isFiltered' => true,
            'expectation' => <<<expect
[debug] Good one
expect
        ];
    }

    public function testDefaultIsFilteredIsFalse(): void
    {
        $output = new BufferedOutput();
        $filterableConsoleLogger = new FilterableConsoleLogger(
            $output,
            [LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,]
        );
        $expectation = '[notice] Notice Me :(';
        $filterableConsoleLogger->log(LogLevel::NOTICE, 'Notice Me :(');

        // trimmed to avoid ending newlines
        $actual = trim($output->fetch());

        self::assertSame($expectation, $actual);
    }

    public function testFilteringRespectsBeingEnabled(): void
    {
        $output = new NullOutput();
        $filterableConsoleLogger = new FilterableConsoleLogger($output);
        $filterableConsoleLogger->setFiltering(true);

        self::assertTrue($filterableConsoleLogger->isFiltered());
    }

    public function testIsFilteredIsFalseByDefault(): void
    {
        $output = new NullOutput();
        $filterableConsoleLogger = new FilterableConsoleLogger($output);

        self::assertFalse($filterableConsoleLogger->isFiltered());
    }

    public function testIsFilteredIsTruthfulWhenFalse(): void
    {
        $output = new NullOutput();
        $filterableConsoleLogger = new FilterableConsoleLogger($output);
        $filterableConsoleLogger->setFiltering(false);

        self::assertFalse($filterableConsoleLogger->isFiltered());
    }

    /**
     * @dataProvider getInputsForFilteredOptionsTest
     */
    public function testLogRespectsFilteredOption(array $logs, bool $isFiltered, string $expectation): void
    {
        $output = new BufferedOutput();
        $filterableConsoleLogger = (new FilterableConsoleLogger(
            $output,
            [
                LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::DEBUG => OutputInterface::VERBOSITY_NORMAL,
            ]
        ))->setFiltering($isFiltered);

        if (count($logs) === 0) {
            // don't allow weak test
            self::fail('Test case did not provide any logs to message');

            return;
        }

        // Loops, in tests are really bad. Don't copy me
        foreach ($logs as $log) {
            // calling methods like this is also not ideal
            call_user_func_array([$filterableConsoleLogger, 'log'], $log);
        }

        // trimmed to avoid ending newlines
        $actual = trim($output->fetch());

        self::assertSame($expectation, $actual);
    }
}
