<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Printer;

use Nette\Utils\Strings;
use SimonSchaufi\RuleDocGenerator\Printer\CodeSamplePrinter\CodeSamplePrinter;
use SimonSchaufi\RuleDocGenerator\Text\KeywordHighlighter;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;
use Webmozart\Assert\Assert;

final class RuleDefinitionsPrinter
{
    private CodeSamplePrinter $codeSamplePrinter;

    private KeywordHighlighter $keywordHighlighter;

    public function __construct(
        CodeSamplePrinter $codeSamplePrinter,
        KeywordHighlighter $keywordHighlighter
    ) {
        $this->keywordHighlighter = $keywordHighlighter;
        $this->codeSamplePrinter = $codeSamplePrinter;
    }

    /**
     * @param DocumentedRule[] $ruleDefinitions
     * @return string[]
     */
    public function print(array $ruleDefinitions, ?int $categorizeLevel): array
    {
        $ruleCount = count($ruleDefinitions);

        $lines = [];
        $lines[] = sprintf('# %d Rules Overview', $ruleCount);

        if ($categorizeLevel) {
            $ruleDefinitionsByCategory = $this->groupDefinitionsByCategory($ruleDefinitions, $categorizeLevel);

            $categoryMenuLines = $this->createCategoryMenu($ruleDefinitionsByCategory);
            $lines = [...$lines, ...$categoryMenuLines];

            foreach ($ruleDefinitionsByCategory as $category => $ruleDefinitions) {
                $lines[] = '## ' . $category;
                $lines = $this->printRuleDefinitions($ruleDefinitions, $lines, $categorizeLevel);
            }
        } else {
            $lines = $this->printRuleDefinitions($ruleDefinitions, $lines, null);
        }

        return $lines;
    }

    /**
     * @param DocumentedRule[] $ruleDefinitions
     * @return array<string, DocumentedRule[]>
     */
    private function groupDefinitionsByCategory(array $ruleDefinitions, int $categorizeLevel): array
    {
        $ruleDefinitionsByCategory = [];

        // have a convention from namespace :)
        foreach ($ruleDefinitions as $documentedRule) {
            $category = $this->resolveCategory($documentedRule, $categorizeLevel);
            $ruleDefinitionsByCategory[$category][] = $documentedRule;
        }

        ksort($ruleDefinitionsByCategory);

        return $ruleDefinitionsByCategory;
    }

    /**
     * @param DocumentedRule[] $ruleDefinitions
     * @param string[] $lines
     * @return string[]
     */
    private function printRuleDefinitions(array $ruleDefinitions, array $lines, ?int $categorizeLevel): array
    {
        foreach ($ruleDefinitions as $documentedRule) {
            if ($categorizeLevel) {
                $lines[] = '### ' . $documentedRule->getRuleShortClass();
            } else {
                $lines[] = '## ' . $documentedRule->getRuleShortClass();
            }

            $lines[] = $this->keywordHighlighter->highlight($documentedRule->getDescription());

            if ($documentedRule->isConfigurable()) {
                $lines[] = ':wrench: **configure it!**';
            }

            $lines[] = '- class: [`' . $documentedRule->getRuleClass() . '`](' . $documentedRule->getRuleFilePath() . ')';

            $codeSampleLines = $this->codeSamplePrinter->print($documentedRule);
            $lines = array_merge($lines, $codeSampleLines);
        }

        return $lines;
    }

    /**
     * @param array<string, DocumentedRule[]> $ruleDefinitionsByCategory
     * @return string[]
     */
    private function createCategoryMenu(array $ruleDefinitionsByCategory): array
    {
        $lines = [];
        $lines[] = '<br>';
        $lines[] = '## Categories';

        foreach ($ruleDefinitionsByCategory as $category => $ruleDefinitions) {
            $categoryLink = strtolower(Strings::webalize($category));

            $lines[] = sprintf('- [%s](#%s) (%d)', $category, $categoryLink, count($ruleDefinitions));
        }

        $lines[] = '<br>';

        return $lines;
    }

    private function resolveCategory(DocumentedRule $documentedRule, int $categorizeLevel): string
    {
        $classNameParts = explode('\\', $documentedRule->getRuleClass());

        // get one namespace before last by convention
        array_pop($classNameParts);

        $categoryName = null;
        while ($categorizeLevel > 0) {
            // get one namespace before last by convention
            $categoryName = array_pop($classNameParts);
            --$categorizeLevel;
        }

        // remove _ as not part of title
        Assert::string($categoryName);

        return trim($categoryName, '_');
    }
}
