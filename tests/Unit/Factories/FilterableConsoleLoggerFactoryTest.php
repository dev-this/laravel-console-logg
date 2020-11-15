<?php

declare(strict_types=1);

namespace Tests\Unit\Factories;

use DevThis\ConsoleLogg\Console\FilterableConsoleLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Doubles\Spies\Factories\FilterableConsoleLoggerFactorySpy;

/**
 * @covers \DevThis\ConsoleLogg\Factories\FilterableConsoleLoggerFactory
 */
class FilterableConsoleLoggerFactoryTest extends TestCase
{
    public function getInputsForTestingArguments(): iterable
    {
        yield 'is filtered true provided' => [
            'isFiltered' => true,
            'filteredExpectation' => true
        ];

        yield 'is filtered false' => [
            'isFiltered' => false,
            'filteredExpectation' => false
        ];

        yield 'is filtered provided null' => [
            'isFiltered' => null,
            'filteredExpectation' => null
        ];
    }

    /**
     * Unfortunately we're testing the spy mainly here
     * This is a fragile test.
     *
     * @todo work out stronger assertions
     *
     * @dataProvider getInputsForTestingArguments
     */
    public function testCreateRespectsArguments(?bool $isFiltered, ?bool $filteredExpectation): void
    {
        $factory = new FilterableConsoleLoggerFactorySpy();
        $output = new NullOutput();
        $consoleLogger = $factory->create($output, $isFiltered);

        // feels bad using a spy, but it's impossible to gauge this without
        self::assertSame($output, $factory->getLastOutput());
        self::assertSame($filteredExpectation, $factory->getLastIsFilteredValue());
        // weak assertion but still valid because we only know it returns the interface, not concrete
        self::assertInstanceOf(FilterableConsoleLogger::class, $consoleLogger);
    }
}
