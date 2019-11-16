<?php

namespace App\Users\UI\Console\Command;

use DateTime;
use Ramsey\Uuid\Uuid;
use App\Users\Application\Command\CreateNewUser;
use System\UI\Console\Command\BaseConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use App\Users\UI\Validator\UserValidator;

class CreateNewUserConsoleCommand extends BaseConsoleCommand
{
    use UserValidator;

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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = $input->getArguments();

        $result = $this->validateNewUser($params);

        if (!$result->isValid()) {
            array_map(static function ($message) use ($output) {
                $output->write('<error>');
                $output->writeln($message);
                $output->write('</error>');
            }, $result->getMessages());
            exit;
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            Uuid::uuid4()->toString(),
            (string) $params->email,
            (string) $params->password,
            (int) false,
            new DateTime(),
            new DateTime()
        );

        $this->commandBus->handle($command);

        $output->writeln('<info>User successfully added!</info>');
    }
}
