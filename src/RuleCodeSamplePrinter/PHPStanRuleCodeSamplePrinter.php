<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter;

use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface;
use SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter\BadGoodCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\Printer\Markdown\MarkdownCodeWrapper;
use SimonSchaufi\RuleDocGenerator\Printer\NeonPrinter;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;

final class PHPStanRuleCodeSamplePrinter implements RuleCodeSamplePrinterInterface
{
    private NeonPrinter $neonPrinter;

    private MarkdownCodeWrapper $markdownCodeWrapper;

    private BadGoodCodeSamplePrinter $badGoodCodeSamplePrinter;

    public function __construct(
        NeonPrinter $neonPrinter,
        MarkdownCodeWrapper $markdownCodeWrapper,
        BadGoodCodeSamplePrinter $badGoodCodeSamplePrinter
    ) {
        $this->badGoodCodeSamplePrinter = $badGoodCodeSamplePrinter;
        $this->markdownCodeWrapper = $markdownCodeWrapper;
        $this->neonPrinter = $neonPrinter;
    }

    public function isMatch(string $class): bool
    {
        return is_a($class, 'PHPStan\Rules\Rule', true);
    }

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample, DocumentedRule $documentedRule): array
    {
        if ($codeSample instanceof ConfiguredCodeSample) {
            return $this->printConfigurableCodeSample($codeSample, $documentedRule);
        }

        return $this->badGoodCodeSamplePrinter->print($codeSample);
    }

    /**
     * @return string[]
     */
    private function printConfigurableCodeSample(
        ConfiguredCodeSample $configuredCodeSample,
        DocumentedRule $documentedRule
    ): array {
        $lines = [];

        $phpStanNeon = [
            'services' => [
                [
                    'class' => $documentedRule->getRuleClass(),
                    'tags' => ['phpstan.rules.rule'],
                    'arguments' => $configuredCodeSample->getConfiguration(),
                ],
            ],
        ];

        $printedNeon = $this->neonPrinter->printNeon($phpStanNeon);
        $lines[] = $this->markdownCodeWrapper->printYamlCode($printedNeon);

        $lines[] = '↓';

        $newLines = $this->badGoodCodeSamplePrinter->print($configuredCodeSample);
        return [...$lines, ...$newLines];
    }
}
