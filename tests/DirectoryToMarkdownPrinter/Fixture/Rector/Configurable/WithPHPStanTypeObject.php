<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\Rector\Configurable;

use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\RectorInterface;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\ConfigurableRuleInterface;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Source\SomeValueObjectWrapper;

final class WithPHPStanTypeObject implements RectorInterface, ConfigurableRuleInterface, DocumentedRuleInterface
{
    /**
     * @var string
     */
    public const ADDED_ARGUMENTS = 'added_arguments';

    public function getRuleDefinition(): RuleDefinition
    {
        $objectType = new ObjectType('SomeObject');

        $exampleConfiguration = [
            new SomeValueObjectWrapper($objectType),
        ];

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
                $exampleConfiguration
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
