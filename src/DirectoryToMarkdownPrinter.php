<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator;

use SimonSchaufi\RuleDocGenerator\Exception\ShouldNotHappenException;
use SimonSchaufi\RuleDocGenerator\FileSystem\RuleDefinitionClassesFinder;
use SimonSchaufi\RuleDocGenerator\Printer\RuleDefinitionsPrinter;
use SimonSchaufi\RuleDocGenerator\ValueObject\RuleClassWithFilePath;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @see \SimonSchaufi\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\DirectoryToMarkdownPrinterTest
 */
final class DirectoryToMarkdownPrinter
{
    private RuleDefinitionClassesFinder $classByTypeFinder;

    private SymfonyStyle $symfonyStyle;

    private RuleDefinitionsResolver $ruleDefinitionsResolver;

    private RuleDefinitionsPrinter $ruleDefinitionsPrinter;

    public function __construct(
        RuleDefinitionClassesFinder $classByTypeFinder,
        SymfonyStyle $symfonyStyle,
        RuleDefinitionsResolver $ruleDefinitionsResolver,
        RuleDefinitionsPrinter $ruleDefinitionsPrinter
    ) {
        $this->ruleDefinitionsPrinter = $ruleDefinitionsPrinter;
        $this->ruleDefinitionsResolver = $ruleDefinitionsResolver;
        $this->symfonyStyle = $symfonyStyle;
        $this->classByTypeFinder = $classByTypeFinder;
    }

    /**
     * @param string[] $directories
     * @param string[] $skipTypes
     */
    public function print(string $workingDirectory, array $directories, ?int $categorizeLevel, array $skipTypes): string
    {
        // 1. collect documented rules in provided path
        $ruleWithFilePaths = $this->classByTypeFinder->findAndCreateRuleWithFilePaths($directories, $workingDirectory);

        $ruleWithFilePaths = $this->filterOutSkippedTypes($ruleWithFilePaths, $skipTypes);
        if ($ruleWithFilePaths === []) {
            // we need at least some classes
            throw new ShouldNotHappenException(sprintf('No documented classes found in "%s" directories', implode('","', $directories)));
        }

        $message = sprintf('Found %d documented rule classes', count($ruleWithFilePaths));
        $this->symfonyStyle->note($message);

        $classes = array_map(
            static fn (RuleClassWithFilePath $ruleClassWithFilePath): string => $ruleClassWithFilePath->getClass(),
            $ruleWithFilePaths
        );

        $this->symfonyStyle->listing($classes);

        // 2. create rule definition collection
        $this->symfonyStyle->note('Resolving rule definitions');

        $ruleDefinitions = $this->ruleDefinitionsResolver->resolveFromClassNames($ruleWithFilePaths);

        // 3. print rule definitions to markdown lines
        $this->symfonyStyle->note('Printing rule definitions');
        $markdownLines = $this->ruleDefinitionsPrinter->print($ruleDefinitions, $categorizeLevel);

        $fileContent = '';
        foreach ($markdownLines as $markdownLine) {
            $fileContent .= trim($markdownLine) . PHP_EOL . PHP_EOL;
        }

        return rtrim($fileContent) . PHP_EOL;
    }

    /**
     * @param RuleClassWithFilePath[] $ruleClassWithFilePaths
     * @param string[] $skipTypes
     * @return RuleClassWithFilePath[]
     */
    private function filterOutSkippedTypes(array $ruleClassWithFilePaths, array $skipTypes): array
    {
        // remove deprecated classes, as no point in promoting them
        $ruleClassWithFilePaths = array_filter($ruleClassWithFilePaths, static fn (RuleClassWithFilePath $ruleClassWithFilePath): bool => $ruleClassWithFilePath->isDeprecated() === false);

        if ($skipTypes === []) {
            return $ruleClassWithFilePaths;
        }

        return array_filter(
            $ruleClassWithFilePaths,
            static function (RuleClassWithFilePath $ruleClassWithFilePath) use ($skipTypes): bool {
                foreach ($skipTypes as $skipType) {
                    if (is_a($ruleClassWithFilePath->getClass(), $skipType, true)) {
                        return false;
                    }
                }

                // nothing to skip
                return true;
            }
        );
    }
}
