<?php

namespace System\UI\Console\Command;

use ZipArchive;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ModuleBuilderCommand extends Command
{
    protected static $defaultName = 'system:module-builder';

    protected $output;

    protected function configure()
    {
        $this
            ->setDescription('Builds new app module.')
            ->setHelp('Creates file and dir structure for new module.')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('module_name', InputArgument::REQUIRED),
                    new InputArgument('module_archive', InputArgument::REQUIRED),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $params = (object) $input->getArguments();

        $moduleName     = $this->fixModuleName($params->module_name);
        $moduleArchive  = $params->module_archive;
        $resourcePath   = resources_path().'module/'.$moduleArchive;

        if (!file_exists($resourcePath)) {
            $output->writeln("<error>Cannot find archive in location: {$resourcePath}</error>");
            exit;
        }

        if (file_exists(app_path().$moduleName)) {
            $output->writeln("<error>Module {$moduleName} already exists</error>");
            exit;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("Should I proceed with creating module {$moduleName}? (y/n) ", false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('Bye!');
            exit;
        }

        $this->createModule($moduleName, $resourcePath);
    }

    protected function fixModuleName(string $moduleName): string
    {
        if (substr($moduleName, -1) !== 's') {
            return $moduleName.'s';
        }

        return $moduleName;
    }

    protected function createModule(string $moduleName, string $resourcePath): void
    {
        $this->createModuleDir(app_path().$moduleName);

        $this->extractArchive($resourcePath, app_path().$moduleName);
    }

    protected function createModuleDir(string $path): void
    {
        $this->output->writeln('Creating module directory...');
        mkdir($path, 0777, true);
    }

    protected function extractArchive(string $resourcePath, string $modulePath): void
    {
        $this->output->writeln('Extracting archive...');

        $zip = new ZipArchive;
        if ($zip->open($resourcePath) === true) {
            $zip->extractTo($modulePath);
            $zip->close();
            $this->output->writeln('Extraction complete');
        } else {
            $this->output->writeln('<error>Could not extract archive from path: '.$resourcePath.'</error>');
        }
    }
}
