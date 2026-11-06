<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter;

use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\CodeSampleInterface;
use Rector\RuleDoc\Contract\ConfigurableRuleInterface;
use SimonSchaufi\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface;
use SimonSchaufi\RuleDocGenerator\Exception\ConfigurationBoundException;
use SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter\ECSRuleCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter\PHPStanRuleCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter\RectorRuleCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;

/**
 * @see \SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\DirectoryToMarkdownPrinterTest
 */
final class CodeSamplePrinter
{
    /**
     * @var RuleCodeSamplePrinterInterface[]
     */
    private array $ruleCodeSamplePrinters;

    public function __construct(
        ECSRuleCodeSamplePrinter $ecsRuleCodeSamplePrinter,
        PHPStanRuleCodeSamplePrinter $phpStanRuleCodeSamplePrinter,
        RectorRuleCodeSamplePrinter $rectorRuleCodeSamplePrinter
    ) {
        $this->ruleCodeSamplePrinters = [
            $ecsRuleCodeSamplePrinter,
            $phpStanRuleCodeSamplePrinter,
            $rectorRuleCodeSamplePrinter,
        ];
    }

    /**
     * @return string[]
     */
    public function print(DocumentedRule $documentedRule): array
    {
        $lines = [];

        foreach ($documentedRule->getCodeSamples() as $codeSample) {
            $this->ensureConfigureRuleBoundsConfiguredCodeSample($codeSample, $documentedRule);

            foreach ($this->ruleCodeSamplePrinters as $ruleCodeSamplePrinter) {
                if (! $ruleCodeSamplePrinter->isMatch($documentedRule->getRuleClass())) {
                    continue;
                }

                $newLines = $ruleCodeSamplePrinter->print($codeSample, $documentedRule);
                $lines = array_merge($lines, $newLines);
                break;
            }

            $lines[] = '<br>';
        }

        return $lines;
    }

    private function ensureConfigureRuleBoundsConfiguredCodeSample(
        CodeSampleInterface $codeSample,
        DocumentedRule $documentedRule
    ): void {
        // ensure the configured rule + configure code sample are used
        if ($codeSample instanceof ConfiguredCodeSample) {
            if ($this->isConfigurableRule($documentedRule->getRuleClass())) {
                return;
            }

            $errorMessage = sprintf(
                'The "%s" rule has configure code sample and must implements "%s" interface',
                $documentedRule->getRuleClass(),
                ConfigurableRuleInterface::class
            );
            throw new ConfigurationBoundException($errorMessage);
        }

        if (! $this->isConfigurableRule($documentedRule->getRuleClass())) {
            return;
        }

        $errorMessage = sprintf(
            'The "%s" rule implements "%s" and code sample must be "%s"',
            $documentedRule->getRuleClass(),
            ConfigurableRuleInterface::class,
            ConfiguredCodeSample::class,
        );
        throw new ConfigurationBoundException($errorMessage);
    }

    private function isConfigurableRule(string $ruleClass): bool
    {
        return is_a($ruleClass, ConfigurableRuleInterface::class, true);
    }
}
