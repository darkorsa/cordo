<?php

declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use Zend\Mail\Message;
use System\Application\Queue\AbstractReceiver;
use App\Users\Application\Command\SendUserWelcomeMessage;
use System\Infractructure\Mailer\ZendMail\MailerInterface;

class SendUserWelcomeMessageHandler extends AbstractReceiver
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(SendUserWelcomeMessage $command): void
    {
        $message = new Message();
        $message->addTo($command->getEmail())
            ->addFrom('noreply@codeninjas.pl')
            ->setSubject('Welcome aboard!')
            ->setBody("Congratulations you've just created a new account!");

        $this->mailer->send($message);
    }
}
