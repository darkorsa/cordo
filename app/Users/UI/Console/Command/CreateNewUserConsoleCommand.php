<?php

namespace App\Users\UI\Console\Command;

use DateTime;
use Ramsey\Uuid\Uuid;
use Particle\Validator\Validator;
use Particle\Validator\ValidationResult;
use App\Users\Application\Service\UserService;
use App\Users\Application\Command\CreateNewUser;
use System\UI\Console\Command\BaseConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Particle\Validator\Exception\InvalidValueException;
use System\Application\Exception\ResourceNotFoundException;

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
                    new InputArgument('password', InputArgument::REQUIRED)
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = $input->getArguments();

        $result = $this->validate($params);

        if (!$result->isValid()) {
            array_map(function ($message) use ($output) {
                $output->write('<error>');
                $output->writeln($message);
                $output->write('</error>');
            }, $result->getMessages());
            exit;
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            (string) Uuid::uuid4(),
            (string) $params->email,
            (string) $params->password,
            (int) false,
            new DateTime(),
            new DateTime()
        );

        $this->commandBus->handle($command);

        $output->writeln('<info>User successfully added!</info>');
    }

    private function validate(array $params): ValidationResult
    {
        $service = $this->container->get(UserService::class);
        
        $validator = new Validator;
        $validator->required('password')->lengthBetween(6, 18);
        $validator->required('email')
            ->lengthBetween(6, 50)
            ->email()
            ->callback(function ($value) use ($service) {
                try {
                    $service->getOneByEmail($value);
                    throw new InvalidValueException('Email address us not unique', 'Unique::EMAIL_NOT_UNIQUE');
                } catch (ResourceNotFoundException $ex) {
                    return true;
                }
            });


        return $validator->validate($params);
    }
}
