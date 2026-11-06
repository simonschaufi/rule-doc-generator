<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter;

use SimonSchaufi\RuleDocGenerator\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\Printer\Markdown\MarkdownCodeWrapper;

final class BadGoodCodeSamplePrinter
{
    private MarkdownCodeWrapper $markdownCodeWrapper;

    public function __construct(
        MarkdownCodeWrapper $markdownCodeWrapper
    ) {
        $this->markdownCodeWrapper = $markdownCodeWrapper;
    }

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample): array
    {
        return [
            $this->markdownCodeWrapper->printPhpCode($codeSample->getBadCode()),
            ':x:',
            '<br>',
            $this->markdownCodeWrapper->printPhpCode($codeSample->getGoodCode()),
            ':+1:',
        ];
    }
}
