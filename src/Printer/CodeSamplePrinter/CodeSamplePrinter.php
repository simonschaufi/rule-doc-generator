<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter;

use SimonSchaufi\RuleDocGenerator\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use SimonSchaufi\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface;
use SimonSchaufi\RuleDocGenerator\Exception\ConfigurationBoundException;
use SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter\ECSRuleCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter\PHPStanRuleCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\RuleCodeSamplePrinter\RectorRuleCodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

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
    public function print(RuleDefinition $ruleDefinition): array
    {
        $lines = [];

        foreach ($ruleDefinition->getCodeSamples() as $codeSample) {
            $this->ensureConfigureRuleBoundsConfiguredCodeSample($codeSample, $ruleDefinition);

            foreach ($this->ruleCodeSamplePrinters as $ruleCodeSamplePrinter) {
                if (! $ruleCodeSamplePrinter->isMatch($ruleDefinition->getRuleClass())) {
                    continue;
                }

                $newLines = $ruleCodeSamplePrinter->print($codeSample, $ruleDefinition);
                $lines = array_merge($lines, $newLines);
                break;
            }

            $lines[] = '<br>';
        }

        return $lines;
    }

    private function ensureConfigureRuleBoundsConfiguredCodeSample(
        CodeSampleInterface $codeSample,
        RuleDefinition $ruleDefinition
    ): void {
        // ensure the configured rule + configure code sample are used
        if ($codeSample instanceof ConfiguredCodeSample) {
            if (is_a($ruleDefinition->getRuleClass(), ConfigurableRuleInterface::class, true)) {
                return;
            }

            $errorMessage = sprintf(
                'The "%s" rule has configure code sample and must implements "%s" interface',
                $ruleDefinition->getRuleClass(),
                ConfigurableRuleInterface::class
            );
            throw new ConfigurationBoundException($errorMessage);
        }

        if (! is_a($ruleDefinition->getRuleClass(), ConfigurableRuleInterface::class, true)) {
            return;
        }

        $errorMessage = sprintf(
            'The "%s" rule implements "%s" and code sample must be "%s"',
            $ruleDefinition->getRuleClass(),
            ConfigurableRuleInterface::class,
            ConfiguredCodeSample::class,
        );
        throw new ConfigurationBoundException($errorMessage);
    }
}
