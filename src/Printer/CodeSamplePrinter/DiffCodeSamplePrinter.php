<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter;

use Rector\RuleDoc\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\Printer\Markdown\MarkdownDiffer;

final class DiffCodeSamplePrinter
{
    private MarkdownDiffer $markdownDiffer;

    public function __construct(
        MarkdownDiffer $markdownDiffer
    ) {
        $this->markdownDiffer = $markdownDiffer;
    }

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample): array
    {
        $diffCode = $this->markdownDiffer->diff(
            $codeSample->getBadCode(),
            $codeSample->getGoodCode()
        );

        return [$diffCode];
    }
}
