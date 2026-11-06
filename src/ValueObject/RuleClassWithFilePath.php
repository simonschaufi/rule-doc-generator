<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\ValueObject;

final class RuleClassWithFilePath
{
    private string $class;

    private string $path;

    private bool $isDeprecated;

    public function __construct(
        string $class,
        string $path,
        bool $isDeprecated
    ) {
        $this->isDeprecated = $isDeprecated;
        $this->path = $path;
        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function isDeprecated(): bool
    {
        return $this->isDeprecated;
    }
}
