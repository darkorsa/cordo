<?php

namespace App\Users\Application\Command\Handler;

use Psr\Log\LoggerInterface;
use App\Users\Application\Command\SendUserWelcomeMessage;
use System\Application\Queue\AbstractReceiver;

class SendUserWelcomeMessageHandler extends AbstractReceiver
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function handle(SendUserWelcomeMessage $command): void
    {
        $this->logger->error('message sent to: '.$command->email().'!');
    }
}
