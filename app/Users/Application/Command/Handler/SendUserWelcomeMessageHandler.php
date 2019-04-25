<?php

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
                ->setSubject('Potwierdzenie założenia konta')
                ->setBody("Gratujacje konto zostało założone!");

        $this->mailer->send($message);
    }
}
