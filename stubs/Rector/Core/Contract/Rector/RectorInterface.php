<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Rector;

use Rector\RuleDoc\Contract\DocumentedRuleInterface;

if (interface_exists(RectorInterface::class)) {
    return;
}

interface RectorInterface extends DocumentedRuleInterface
{
}
