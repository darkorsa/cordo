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
        $messageText = sprintf(
            'Exception occured in file %s on line %d with message: %s',
            $exception->getFile(),
            $exception->getLine(),
            $exception->getMessage()
        );

        $message = new Message();
        foreach ($this->to as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message->addTo($email);
            }
        }
        $message->addFrom($this->config->from)
                ->setSubject('Critical Error')
                ->setBody($messageText);

        $this->mailer->send($message);
    }
}
