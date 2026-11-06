<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\ValueObject\CodeSample;

use SimonSchaufi\RuleDocGenerator\ValueObject\AbstractCodeSample;

final class ExtraFileCodeSample extends AbstractCodeSample
{
    private string $extraFile;

    public function __construct(
        string $badCode,
        string $goodCode,
        string $extraFile
    ) {
        $this->extraFile = $extraFile;
        parent::__construct($badCode, $goodCode);
    }

    public function getExtraFile(): string
    {
        return $this->extraFile;
    }
}
