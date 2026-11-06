# Rule Doc Generator

Generate Documentation for your Sniffer, Fixer, Rector, PHPStan... any rule in nice styled Markdown syntax with PHP Code examples everyone will understand.

[![Downloads total](https://img.shields.io/packagist/dt/simonschaufi/rule-doc-generator.svg?style=flat-square)](https://packagist.org/packages/simonschaufi/rule-doc-generator/stats)

## Install

```bash
composer require simonschaufi/rule-doc-generator --dev
```

## Usage

To generate documentation from rules, use `generate` command with paths that contain the rules:

```bash
vendor/bin/rule-doc-generator generate src/Rules
```

The file will be generated to `/docs/rules_overview.md` by default. To change that, use `--output-file`:

```bash
vendor/bin/rule-doc-generator generate src/Rules --output-file docs/my-rules.md
```
