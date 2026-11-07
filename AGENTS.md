# AGENTS.md

Instructions for coding agents working on `simonschaufi/rule-doc-generator`.

## Project facts

- PHP `^7.4 || ^8.0`, CI runs 7.4 to 8.4: no constructor promotion,
  `readonly`, enums, named arguments or other PHP 8 only syntax.
- The rule API (`RuleDefinition`, `CodeSample`, `ConfiguredCodeSample`,
  `DocumentedRuleInterface`, `ConfigurableRuleInterface`) comes from
  `Rector\RuleDoc\*` in `rector/rector`. Do not add own copies.
- Services are autowired by `illuminate/container`; only special cases
  are registered in `src/DependencyInjection/ContainerFactory.php`.
- Printers return `string[]` lines; the Markdown in
  `tests/DirectoryToMarkdownPrinter/Expected/` is the output contract.
- New fixture rules go into `tests/DirectoryToMarkdownPrinter/Fixture/`
  and must be added to `DirectoryToMarkdownPrinterTest::provideData()`.

## Where text belongs

| Text | Where | Length |
| --- | --- | --- |
| What changed and why | Commit body | A few lines or bullets, wrapped at 72 |
| What changes for users | Pull request | TL;DR of 1-3 sentences and `Resolves #123` |
| What is wrong and how to reproduce it | Issue | Minimal reproduction |
| Logs, traces, analyses, alternatives | `<details>` below the TL;DR | Only if really needed |
| Why code that looks wrong is correct | Code comment | At most 2 lines |
| Behavior guarded by a test | Test method name | - |

Never repeat the same text in commit, pull request and issue. The pull
request links the issue; the commit explains the change.

## Rules

- Summary line: `[BUGFIX]`, `[FEATURE]`, `[TASK]` or `[DOCS]`, with
  `[!!!]` in front for breaking changes; imperative, at most 72
  characters.
- Footer: `Resolves: #123` or `Related: #123`, one issue per line.
- Do not add `Signed-off-by:`; only the human author does.
- No emojis in commits, pull requests or issues.
- Before you write a long text, write the TL;DR. If the TL;DR is enough,
  stop there.

## Before you commit

Run the same checks as CI:

```bash
composer validate --strict
composer phpstan
composer rector
composer check-cs
composer phpunit
```

`composer fix-cs` applies the coding standard. Rebase onto `main`; no
merge, `fixup!` or `WIP` commits.
