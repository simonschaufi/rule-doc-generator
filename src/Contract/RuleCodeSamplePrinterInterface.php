<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Contract;

use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

interface RuleCodeSamplePrinterInterface
{
    public function isMatch(string $class): bool;

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample, RuleDefinition $ruleDefinition): array;
}
