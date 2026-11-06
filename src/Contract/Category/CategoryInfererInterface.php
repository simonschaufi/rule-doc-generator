<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Contract\Category;

use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;

interface CategoryInfererInterface
{
    public function infer(DocumentedRule $documentedRule): ?string;
}
