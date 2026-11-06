<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\ValueObject\CodeSample;

use Rector\RuleDoc\AbstractCodeSample;

final class ComposerJsonAwareCodeSample extends AbstractCodeSample
{
    private string $composerJson;

    public function __construct(
        string $badCode,
        string $goodCode,
        string $composerJson
    ) {
        $this->composerJson = $composerJson;
        parent::__construct($badCode, $goodCode);
    }

    public function getComposerJson(): string
    {
        return $this->composerJson;
    }
}
