<?php declare(strict_types=1);

namespace System\Application\Mail;

use Zend\Mail\Message;

interface MailerInterface
{
    public function send(Message $message): void;
}
