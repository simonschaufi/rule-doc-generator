<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter;

use SimonSchaufi\RuleDocGenerator\Printer\DirectoryToMarkdownPrinter;
use SimonSchaufi\RuleDocGenerator\Tests\AbstractTestCase;
use SimonSchaufi\RuleDocGenerator\Tests\Fixture\StaticFixtureUpdater;

final class DirectoryToMarkdownPrinterTest extends AbstractTestCase
{
    private DirectoryToMarkdownPrinter $directoryToMarkdownPrinter;

    protected function setUp(): void
    {
        $this->directoryToMarkdownPrinter = $this->make(DirectoryToMarkdownPrinter::class);
    }

    public static function provideData(): \Iterator
    {
        yield [__DIR__ . '/Fixture/PHPStan/Standard', __DIR__ . '/Expected/phpstan/phpstan_content.md'];

        yield [
            __DIR__ . '/Fixture/PHPStan/Configurable',
            __DIR__ . '/Expected/phpstan/configurable_phpstan_content.md',
        ];

        yield [__DIR__ . '/Fixture/PHPCSFixer/Standard', __DIR__ . '/Expected/php-cs-fixer/phpcsfixer_content.md'];

        yield [__DIR__ . '/Fixture/Rector/Standard', __DIR__ . '/Expected/rector/rector_content.md'];

        yield [__DIR__ . '/Fixture/Rector/Standard', __DIR__ . '/Expected/rector/rector_categorized.md', 1];
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $directory, string $expectedFile, ?int $categorizeLevel = null): void
    {
        $fileContent = $this->directoryToMarkdownPrinter->print(__DIR__, [$directory], $categorizeLevel, []);

        StaticFixtureUpdater::updateExpectedFixtureContent($fileContent, $expectedFile);

        $this->assertStringEqualsFile($expectedFile, $fileContent, $directory);
    }
}
