<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Contract;

use SimonSchaufi\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @api
 */
interface DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition;
}
