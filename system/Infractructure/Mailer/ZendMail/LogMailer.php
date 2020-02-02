<?php

declare(strict_types=1);

namespace System\Infractructure\Mailer\ZendMail;

use Monolog\Logger;
use Laminas\Mail\Message;
use Monolog\Handler\StreamHandler;
use Laminas\Mail\Transport\InMemory as InMemoryTransport;

class LogMailer implements MailerInterface
{
    private $logger;

    public function __construct(string $logPath)
    {
        $this->logger = new Logger('maillog');
        $this->logger->pushHandler(new StreamHandler($logPath));
    }

    public function send(Message $message): void
    {
        $transport = new InMemoryTransport();
        $transport->send($message);

        $received = $transport->getLastMessage();

        $this->logMessage($received);
    }

    private function logMessage(Message $message): void
    {
        $this->logger->info($message->toString());
    }
}
