<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\Rector\Standard;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

final class SomeRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Some change', [
            new CodeSample(
                <<<'CODE_SAMPLE'
before
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
after
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
    }

    public function refactor(Node $node)
    {
    }
}
