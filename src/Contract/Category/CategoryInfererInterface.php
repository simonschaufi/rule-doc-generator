<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Contract\Category;

use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

interface CategoryInfererInterface
{
    public function infer(RuleDefinition $ruleDefinition): ?string;
}
