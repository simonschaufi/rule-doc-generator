<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator;

use ReflectionClass;
use SimonSchaufi\RuleDocGenerator\Contract\DocumentedRuleInterface;
use SimonSchaufi\RuleDocGenerator\Exception\ShouldNotHappenException;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleClassWithFilePath;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

final class RuleDefinitionsResolver
{
    /**
     * @param RuleClassWithFilePath[] $ruleClassWithFilePaths
     * @return RuleDefinition[]
     */
    public function resolveFromClassNames(array $ruleClassWithFilePaths): array
    {
        $ruleDefinitions = [];

        foreach ($ruleClassWithFilePaths as $ruleClassWithFilePath) {
            $reflectionClass = new ReflectionClass($ruleClassWithFilePath->getClass());

            $documentedRule = $reflectionClass->newInstanceWithoutConstructor();
            if (! $documentedRule instanceof DocumentedRuleInterface) {
                throw new ShouldNotHappenException();
            }

            $ruleDefinition = $documentedRule->getRuleDefinition();
            $ruleDefinition->setRuleClass($ruleClassWithFilePath->getClass());

            $ruleDefinition->setRuleFilePath($ruleClassWithFilePath->getPath());

            $ruleDefinitions[] = $ruleDefinition;
        }

        return $this->sortByClassName($ruleDefinitions);
    }

    /**
     * @param RuleDefinition[] $ruleDefinitions
     * @return RuleDefinition[]
     */
    private function sortByClassName(array $ruleDefinitions): array
    {
        usort(
            $ruleDefinitions,
            static fn (RuleDefinition $firstRuleDefinition, RuleDefinition $secondRuleDefinition): int => $firstRuleDefinition->getRuleShortClass() <=> $secondRuleDefinition->getRuleShortClass()
        );

        return $ruleDefinitions;
    }
}
