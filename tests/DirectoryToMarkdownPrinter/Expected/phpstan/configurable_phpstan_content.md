# 1 Rules Overview

## SomePHPStanRule

Some description

:wrench: **configure it!**

- class: [`SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\PHPStan\Configurable\SomePHPStanRule`](Fixture/PHPStan/Configurable/SomePHPStanRule.php)

```yaml
services:
    -
        class: SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\PHPStan\Configurable\SomePHPStanRule
        tags: [phpstan.rules.rule]
        arguments:
            someValue: 10
```

↓

```php
bad code
```

:x:

<br>

```php
good code
```

:+1:

<br>
