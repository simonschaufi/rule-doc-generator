<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\PHPStan\Standard;

use PHPStan\Rules\Rule;
use SimonSchaufi\RuleDocGenerator\Contract\DocumentedRuleInterface;
use SimonSchaufi\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

final class SomePHPStanRule implements Rule, DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Some description', [
            new CodeSample(
                <<<'CODE_SAMPLE'
bad code
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
good code
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeType(): string
    {
    }

    public function processNode(\PhpParser\Node $node, \PHPStan\Analyser\Scope $scope): array
    {
    }
}
