<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\PHPStan\Configurable;

use PHPStan\Rules\Rule;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\ConfigurableRuleInterface;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

final class SomePHPStanRule implements Rule, DocumentedRuleInterface, ConfigurableRuleInterface
{
    /**
     * @var int
     */
    private $someValue;

    public function __construct(int $someValue = 10)
    {
        $this->someValue = $someValue;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Some description', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
bad code
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
good code
CODE_SAMPLE
                ,
                [
                    'someValue' => 10,
                ]
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
