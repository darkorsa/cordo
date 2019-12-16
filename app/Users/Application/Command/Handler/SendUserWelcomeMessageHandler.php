<?php

declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use Zend\Mail\Message;
use System\Application\Queue\AbstractReceiver;
use App\Users\Application\Command\SendUserWelcomeMessage;
use League\Plates\Engine;
use System\Infractructure\Mailer\ZendMail\MailerInterface;

class SendUserWelcomeMessageHandler extends AbstractReceiver
{
    private $mailer;

    private $templates;

    public function __construct(MailerInterface $mailer, Engine $templates)
    {
        $this->mailer = $mailer;
        $this->templates = $templates;
    }

    public function handle(SendUserWelcomeMessage $command): void
    {
        $body = $this->templates->render('users::mail/new-user-welcome');

        $message = new Message();
        $message->addTo($command->getEmail())
            ->addFrom('noreply@codeninjas.pl')
            ->setSubject('Welcome aboard!')
            ->setBody($body);

        $this->mailer->send($message);
    }
}
