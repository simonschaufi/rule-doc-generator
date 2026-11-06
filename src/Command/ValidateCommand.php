<?php

declare(strict_types=1);

namespace SimonSchaufi\RuleDocGenerator\Command;

use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use ReflectionClass;
use SimonSchaufi\RuleDocGenerator\FileSystem\RuleDefinitionClassesFinder;
use SimonSchaufi\RuleDocGenerator\ValueObject\DocumentedRule;
use SimonSchaufi\RuleDocGenerator\ValueObject\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ValidateCommand extends Command
{
    private SymfonyStyle $symfonyStyle;

    private RuleDefinitionClassesFinder $ruleDefinitionClassesFinder;

    public function __construct(
        SymfonyStyle $symfonyStyle,
        RuleDefinitionClassesFinder $ruleDefinitionClassesFinder
    ) {
        $this->ruleDefinitionClassesFinder = $ruleDefinitionClassesFinder;
        $this->symfonyStyle = $symfonyStyle;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('validate');

        $this->setDescription('Make sure all rule definitions are not empty and have at least one code sample');

        $this->addArgument(
            Option::PATHS,
            InputArgument::REQUIRED | InputArgument::IS_ARRAY,
            'Path to directory of your project'
        );

        $this->addOption(Option::SKIP_TYPE, null, InputOption::VALUE_REQUIRED, 'Skip specific type in filter');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = (array) $input->getArgument(Option::PATHS);
        $input->getOption(Option::SKIP_TYPE);

        // 1. collect documented rules in provided path
        $classesByFilePaths = $this->ruleDefinitionClassesFinder->findInDirectories($paths);
        if ($classesByFilePaths === []) {
            $this->symfonyStyle->warning('No rules found in provided paths');
            return self::FAILURE;
        }

        $isValid = true;

        foreach ($classesByFilePaths as $ruleClass) {
            $ruleClassReflection = new ReflectionClass($ruleClass);

            $documentedRuleInstance = $ruleClassReflection->newInstanceWithoutConstructor();
            if (! $documentedRuleInstance instanceof DocumentedRuleInterface) {
                $this->symfonyStyle->error(sprintf(
                    'Class "%s" must implement "%s"',
                    $ruleClass,
                    DocumentedRuleInterface::class
                ));

                $isValid = false;
                continue;
            }

            $ruleDefinition = $documentedRuleInstance->getRuleDefinition();
            $ruleDefinition->setRuleFilePath($ruleClassReflection->getFileName() ?: '');
            $documentedRule = new DocumentedRule($ruleClass, $ruleDefinition);

            if (strlen($documentedRule->getDescription()) < 10) {
                $this->symfonyStyle->error(sprintf(
                    'Rule definition "%s" of "%s" is too short. Make it at least 10 chars',
                    $documentedRule->getDescription(),
                    $documentedRule->getRuleClass(),
                ));

                $isValid = false;
            }

            if (count($documentedRule->getCodeSamples()) < 1) {
                $this->symfonyStyle->error(sprintf(
                    'Rule "%s" does not have any code samples. Ad at least one so documentation is clear',
                    $documentedRule->getRuleClass(),
                ));

                $isValid = false;
            }
        }

        if ($isValid === false) {
            return self::FAILURE;
        }

        $this->symfonyStyle->success(sprintf('All "%d" rule definitions are valid', count($classesByFilePaths)));

        return self::SUCCESS;
    }
}
