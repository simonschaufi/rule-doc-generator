<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Source;

use PHPStan\Type\Type;

final class SomeValueObjectWrapper
{
    private ?Type $argumentType;

    public function __construct(?Type $argumentType = null)
    {
        $this->argumentType = $argumentType;
    }
}
