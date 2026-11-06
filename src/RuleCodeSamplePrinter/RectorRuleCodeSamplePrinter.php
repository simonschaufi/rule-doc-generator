<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter;

use Rector\Contract\Rector\RectorInterface;
use SimonSchaufi\RuleDocGenerator\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface;
use SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter\DiffCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

final class RectorRuleCodeSamplePrinter implements RuleCodeSamplePrinterInterface
{
    private DiffCodeSamplePrinter $diffCodeSamplePrinter;

    public function __construct(
        DiffCodeSamplePrinter $diffCodeSamplePrinter
    ) {
        $this->diffCodeSamplePrinter = $diffCodeSamplePrinter;
    }

    public function isMatch(string $class): bool
    {
        return is_a($class, RectorInterface::class, true);
    }

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample, RuleDefinition $ruleDefinition): array
    {
        return $this->diffCodeSamplePrinter->print($codeSample);
    }
}
