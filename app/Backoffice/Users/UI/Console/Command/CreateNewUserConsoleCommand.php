<?php

declare(strict_types=1);

namespace App\Backoffice\Users\UI\Console\Command;

use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Input\InputOption;
use Cordo\Core\UI\Console\Command\BaseConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use App\Backoffice\Users\UI\Validator\NewUserValidator;
use App\Backoffice\Users\Application\Command\CreateNewUser;
use App\Backoffice\Users\UI\Validator\EmailExistsValidation;

class CreateNewUserConsoleCommand extends BaseConsoleCommand
{
    protected static $defaultName = 'users:create-user';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user.')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('email', InputArgument::REQUIRED),
                    new InputArgument('password', InputArgument::REQUIRED),
                ])
            )
            ->addOption('lang', null, InputOption::VALUE_REQUIRED, 'Language');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = $input->getArguments();
        $service = $this->container->get('users.query.service');

        $validator = new NewUserValidator($params);
        $validator->addCallbackValidator('email', new EmailExistsValidation($service));

        if (!$validator->isValid()) {
            array_map(static function ($message) use ($output) {
                $output->write('<error>');
                $output->writeln($message);
                $output->write('</error>');
            }, $validator->messages());
            exit;
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            (string) $params->email,
            (string) $params->password,
            new DateTime()
        );

        $this->commandBus->handle($command);

        $output->writeln('<info>User successfully created.</info>');

        return 0;
    }
}
