<?php

namespace System\UI\Console\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use System\UI\Console\Command\BaseConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

class InitCommand extends BaseConsoleCommand
{
    protected static $defaultName = 'system:init';

    protected $output;

    protected function configure()
    {
        $this
            ->setDescription('Initialize app')
            ->setHelp('Creates neccessary db tables and functions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('entity_manager');

        $helperSet = new HelperSet(array(
            'db' => new ConnectionHelper($em->getConnection()),
            'em' => new EntityManagerHelper($em)
        ));

        $command = $this->getApplication()->find('dbal:import');
        $command->setHelperSet($helperSet);

        $this->importOauthSql($command, $output);
        $this->importUuidSql($command, $output);
        $this->createSchema($em, $output);

        return 0;
    }

    private function importOauthSql(Command $command, $output)
    {
        $arguments = [
            'command' => 'dbal:import',
            'file'    => resources_path() . 'database/sql/oauth.sql',
        ];

        return $command->run(new ArrayInput($arguments), $output);
    }

    private function importUuidSql(Command $command, $output)
    {
        $arguments = [
            'command' => 'dbal:import',
            'file'    => resources_path() . 'database/sql/uuid.sql',
        ];

        return $command->run(new ArrayInput($arguments), $output);
    }

    private function createSchema(EntityManager $em, $output)
    {
        $tool = new SchemaTool($em);
        $classes = [
            $em->getClassMetadata('App\Users\Domain\User'),
            $em->getClassMetadata('System\Module\Auth\Domain\Acl')
        ];
        $tool->createSchema($classes);

        $output->writeln('Schema created successfully.');
    }
}
