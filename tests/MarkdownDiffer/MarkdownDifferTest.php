<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\MarkdownDiffer;

use SimonSchaufi\RuleDocGenerator\Printer\Markdown\MarkdownDiffer;
use SimonSchaufi\RuleDocGenerator\Tests\AbstractTestCase;

final class MarkdownDifferTest extends AbstractTestCase
{
    public function test(): void
    {
        $markdownDiffer = $this->make(MarkdownDiffer::class);

        $currentDiff = $markdownDiffer->diff('old code', 'new code');
        $this->assertStringEqualsFile(__DIR__ . '/Fixture/expected_diff.txt', $currentDiff);
    }
}
