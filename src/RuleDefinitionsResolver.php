<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator;

use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\Exception\ShouldNotHappenException;
use ReflectionClass;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleClassWithFilePath;

final class RuleDefinitionsResolver
{
    /**
     * @param RuleClassWithFilePath[] $ruleClassWithFilePaths
     * @return DocumentedRule[]
     */
    public function resolveFromClassNames(array $ruleClassWithFilePaths): array
    {
        $documentedRules = [];

        foreach ($ruleClassWithFilePaths as $ruleClassWithFilePath) {
            $reflectionClass = new ReflectionClass($ruleClassWithFilePath->getClass());

            $rule = $reflectionClass->newInstanceWithoutConstructor();
            if (! $rule instanceof DocumentedRuleInterface) {
                throw new ShouldNotHappenException(sprintf(
                    'Class "%s" must implement "%s"',
                    $ruleClassWithFilePath->getClass(),
                    DocumentedRuleInterface::class
                ));
            }

            $ruleDefinition = $rule->getRuleDefinition();
            $ruleDefinition->setRuleFilePath($ruleClassWithFilePath->getPath());

            $documentedRules[] = new DocumentedRule($ruleClassWithFilePath->getClass(), $ruleDefinition);
        }

        return $this->sortByClassName($documentedRules);
    }

    /**
     * @param DocumentedRule[] $documentedRules
     * @return DocumentedRule[]
     */
    private function sortByClassName(array $documentedRules): array
    {
        usort(
            $documentedRules,
            static fn (DocumentedRule $firstDocumentedRule, DocumentedRule $secondDocumentedRule): int => $firstDocumentedRule->getRuleShortClass() <=> $secondDocumentedRule->getRuleShortClass()
        );

        return $documentedRules;
    }
}
