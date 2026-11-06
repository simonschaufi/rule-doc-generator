<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\ValueObject;

use Rector\RuleDoc\Contract\CodeSampleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * A rule definition of a documented rule, together with the rule class
 * it was resolved from.
 *
 * Rector's rule definition value object is final and does not know the rule
 * class, so this package carries the class alongside it.
 *
 * @see \SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\DirectoryToMarkdownPrinterTest
 */
final class DocumentedRule
{
    private string $ruleClass;

    private RuleDefinition $ruleDefinition;

    public function __construct(string $ruleClass, RuleDefinition $ruleDefinition)
    {
        $this->ruleDefinition = $ruleDefinition;
        $this->ruleClass = $ruleClass;
    }

    public function getRuleClass(): string
    {
        return $this->ruleClass;
    }

    public function getRuleShortClass(): string
    {
        // get short class name
        return basename(str_replace('\\', '/', $this->ruleClass));
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return $this->ruleDefinition;
    }

    public function getDescription(): string
    {
        return $this->ruleDefinition->getDescription();
    }

    /**
     * @return CodeSampleInterface[]
     */
    public function getCodeSamples(): array
    {
        return $this->ruleDefinition->getCodeSamples();
    }

    public function getRuleFilePath(): string
    {
        return $this->ruleDefinition->getRuleFilePath();
    }

    public function isConfigurable(): bool
    {
        return $this->ruleDefinition->isConfigurable();
    }
}
