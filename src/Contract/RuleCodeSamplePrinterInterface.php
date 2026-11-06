<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Contract;

use Rector\RuleDoc\Contract\CodeSampleInterface;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;

interface RuleCodeSamplePrinterInterface
{
    public function isMatch(string $class): bool;

    /**
     * @return string[]
     */
    public function print(CodeSampleInterface $codeSample, DocumentedRule $documentedRule): array;
}
