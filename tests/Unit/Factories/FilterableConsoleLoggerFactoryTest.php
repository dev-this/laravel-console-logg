<?php

declare(strict_types=1);

namespace Tests\Unit\Factories;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Doubles\Spies\Factories\ConsoleLoggerFactorySpy;

/**
 * @covers \DevThis\ConsoleLogg\Factories\ConsoleLoggerFactory
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
    }

    /**
     * Unfortunately we're testing the spy mainly here
     * This is a fragile test.
     *
     * @todo work out stronger assertions
     *
     * @dataProvider getInputsForTestingArguments
     */
    public function testCreateRespectsArguments(bool $isFiltered, bool $filteredExpectation): void
    {
        $factory = new ConsoleLoggerFactorySpy();
        $output = new NullOutput();

        $consoleLogger = $isFiltered === true ? $factory->createFilterable($output) : $factory->create($output);

        self::assertSame($filteredExpectation, $consoleLogger->isFiltered());
    }
}
