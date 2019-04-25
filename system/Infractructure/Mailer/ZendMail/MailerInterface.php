<?php declare(strict_types=1);

namespace System\Infractructure\Mailer\ZendMail;

use Zend\Mail\Message;

interface MailerInterface
{
    public function send(Message $message): void;
}
