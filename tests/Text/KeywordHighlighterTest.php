<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\Text;

use SimonSchaufi\RuleDocGenerator\Tests\AbstractTestCase;
use SimonSchaufi\RuleDocGenerator\Text\KeywordHighlighter;

final class KeywordHighlighterTest extends AbstractTestCase
{
    private KeywordHighlighter $keywordHighlighter;

    protected function setUp(): void
    {
        $this->keywordHighlighter = $this->make(KeywordHighlighter::class);
    }

    public static function provideData(): \Iterator
    {
        yield ['some @var text', 'some `@var` text'];
        yield ['@param @var text', '`@param` `@var` text'];
        yield ['some @var and @param text', 'some `@var` and `@param` text'];
        yield [
            'Split multiple inline assigns to each own lines default value, to prevent undefined array issues',
            'Split multiple inline assigns to each own lines default value, to prevent undefined array issues',
        ];
        yield [
            'autowire(), autoconfigure(), and public() are required in config service',
            '`autowire()`, `autoconfigure()`, and `public()` are required in config service',
        ];
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $inputText, string $expectedHighlightedText): void
    {
        $highlightedText = $this->keywordHighlighter->highlight($inputText);
        $this->assertSame($expectedHighlightedText, $highlightedText);
    }
}
