<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter;

use Rector\RuleDoc\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface;
use SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter\BadGoodCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter\DiffCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;

final class ECSRuleCodeSamplePrinter implements RuleCodeSamplePrinterInterface
{
    private BadGoodCodeSamplePrinter $badGoodCodeSamplePrinter;

    private DiffCodeSamplePrinter $diffCodeSamplePrinter;

    public function __construct(
        BadGoodCodeSamplePrinter $badGoodCodeSamplePrinter,
        DiffCodeSamplePrinter $diffCodeSamplePrinter
    ) {
        $this->diffCodeSamplePrinter = $diffCodeSamplePrinter;
        $this->badGoodCodeSamplePrinter = $badGoodCodeSamplePrinter;
    }

    public function isMatch(string $class): bool
    {
        if (str_ends_with($class, 'Fixer')) {
            return true;
        }

        return str_ends_with($class, 'Sniff');
    }

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample, DocumentedRule $documentedRule): array
    {
        if (is_a($documentedRule->getRuleClass(), 'PHP_CodeSniffer\Sniffs\Sniff', true)) {
            return $this->badGoodCodeSamplePrinter->print($codeSample);
        }

        return $this->diffCodeSamplePrinter->print($codeSample);
    }
}
