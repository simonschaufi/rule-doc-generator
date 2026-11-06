<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\Rector\Configurable;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use SimonSchaufi\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use SimonSchaufi\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

final class ConfigurableRector implements RectorInterface, ConfigurableRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Some change', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
before
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
after
CODE_SAMPLE
                ,
                [[
                    'key' => 'value',
                    'second_key' => 'second_value',
                ]]
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
