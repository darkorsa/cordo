<?php declare(strict_types=1);

namespace System\Application\Mail;

use Monolog\Logger;
use Zend\Mail\Message;
use Monolog\Handler\StreamHandler;
use Zend\Mail\Transport\InMemory as InMemoryTransport;

class LogMailer implements MailerInterface
{
    private $logPath;
    
    public function __construct(string $logPath)
    {
        $this->logPath = $logPath;
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
        $logger = new Logger('maillog');
        $logger->pushHandler(new StreamHandler($this->logPath));

        $logger->info($message->toString());
    }
}
