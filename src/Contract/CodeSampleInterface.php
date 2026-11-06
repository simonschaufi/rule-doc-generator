<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Contract;

interface CodeSampleInterface
{
    public function getGoodCode(): string;

    public function getBadCode(): string;
}
