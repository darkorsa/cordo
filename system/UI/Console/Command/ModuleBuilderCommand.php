<?php

namespace System\UI\Console\Command;

use ZipArchive;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
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
        $resourcePath   = resources_path() . 'module/' . $moduleArchive;

        if (!file_exists($resourcePath)) {
            $output->writeln("<error>Cannot find archive in location: {$resourcePath}</error>");
            exit;
        }

        if (file_exists(app_path() . $moduleName)) {
            $output->writeln("<error>Module {$moduleName} already exists</error>");
            exit;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("Should I proceed with creating module {$moduleName}? (y/n) ", false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('Bye!');
            exit;
        }

        $this->buildModule($moduleName, $resourcePath);
    }

    protected function buildModule(string $moduleName, string $resourcePath): void
    {
        $modulePath = app_path() . $moduleName;

        $this->createModuleDir($modulePath);

        $this->extractArchive($resourcePath, $modulePath);

        $this->renameFiles($modulePath, $moduleName);

        $this->parseFiles($modulePath, $moduleName);

        $this->output->writeln('Successfully done!');
    }

    protected function createModuleDir(string $path): void
    {
        $this->output->writeln('Creating module directory...');
        mkdir($path, 0777, true);
    }

    protected function extractArchive(string $resourcePath, string $modulePath): void
    {
        $this->output->writeln('Extracting archive...');

        $zip = new ZipArchive();
        if ($zip->open($resourcePath) === true) {
            $zip->extractTo($modulePath);
            $zip->close();
            $this->output->writeln('Extraction complete');
        } else {
            $this->output->writeln('<error>Could not extract archive from path: ' . $resourcePath . '</error>');
        }
    }

    protected function renameFiles(string $modulePath, string $moduleName): void
    {
        $this->output->writeln('Renaming files...');

        $directory = new RecursiveDirectoryIterator($modulePath, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            $replacements = $this->getReplacements($moduleName);

            $renamed = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $file->getPathname()
            );

            rename($file->getPathname(), $renamed);
        }
    }

    protected function parseFiles(string $modulePath, string $moduleName): void
    {
        $this->output->writeln('Parsing files...');

        $directory = new RecursiveDirectoryIterator($modulePath, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            $replacements = $this->getReplacements($moduleName);

            $fileContent = str_replace(
                array_keys($replacements),
                array_values($replacements),
                (string) file_get_contents($file->getPathname())
            );

            file_put_contents($file->getPathname(), $fileContent);
        }
    }

    protected function getReplacements(string $moduleName): array
    {
        return [
            '[entity]'      => strtolower($this->getSingular($moduleName)),
            '[entities]'    => strtolower($moduleName),
            '[Entity]'      => $this->getSingular($moduleName),
            '[Entities]'    => $moduleName,
        ];
    }

    protected function fixModuleName(string $moduleName): string
    {
        if (substr($moduleName, -1) !== 's') {
            return $moduleName . 's';
        }

        return $moduleName;
    }

    protected function getSingular(string $moduleName): string
    {
        return substr($moduleName, -1) === 's' ? substr($moduleName, 0, -1) : $moduleName;
    }
}
