<?php declare(strict_types=1);

namespace System\Application\Error\Handler;

use Throwable;
use Zend\Mail\Message;
use System\Application\Error\ErrorHandlerInterface;
use System\Infractructure\Mailer\ZendMail\MailerFactory;

class EmailErrorHandler implements ErrorHandlerInterface
{
    private $to;
    
    private $config;
    
    private $mailer;

    public function __construct()
    {   
        $config = require root_path() . 'config/mail.php';
        $error  = require root_path() . 'config/error.php';

        $this->mailer   = MailerFactory::factory($config);
        $this->to       = (array) $error['error_reporting_emails'];
        $this->config   = (object) $config;
    }

    public function handle(Throwable $exception): void
    {
        $message = new Message();
        foreach ($this->to as $email) {
            $message->addTo($email);
        }
        $message->addFrom($this->config->from)
                ->setSubject('Critical Error')
                ->setBody($exception->getMessage());

        $this->mailer->send($message);
    }
}