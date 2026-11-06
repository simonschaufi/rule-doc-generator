<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Rector;

use SimonSchaufi\RuleDocGenerator\Contract\DocumentedRuleInterface;

if (interface_exists(RectorInterface::class)) {
    return;
}

interface RectorInterface extends DocumentedRuleInterface
{
}
