<?php

declare(strict_types=1);

namespace App\Users\Application\Command\Handler;

use Laminas\Mail\Message;
use League\Plates\Engine;
use Symfony\Component\Translation\Translator;
use System\Application\Queue\AbstractReceiver;
use App\Users\Application\Command\SendUserWelcomeMessage;
use System\Infractructure\Mailer\ZendMail\MailerInterface;

class SendUserWelcomeMessageHandler extends AbstractReceiver
{
    private $mailer;

    private $templates;

    private $translator;

    public function __construct(MailerInterface $mailer, Engine $templates, Translator $translator)
    {
        $this->mailer = $mailer;
        $this->templates = $templates;
        $this->translator = $translator;
    }

    public function handle(SendUserWelcomeMessage $command): void
    {
        $body = $this->templates->render('users::mail/new-user-welcome', [
            'message' => $this->translator->trans('welcome.mail.message', [], 'mail', $command->getLocale())
        ]);
        $subject = $this->translator->trans('welcome.mail.subject', [], 'mail', $command->getLocale());

        $message = new Message();
        $message->addTo($command->getEmail())
            ->addFrom('noreply@codeninjas.pl')
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($message);
    }
}
