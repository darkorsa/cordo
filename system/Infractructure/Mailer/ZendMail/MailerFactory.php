<?php declare(strict_types=1);

namespace System\Infractructure\Mailer\ZendMail;

use InvalidArgumentException;
use System\Infractructure\Mailer\ZendMail\LogMailer;

class MailerFactory
{
    public static function factory(array $config): MailerInterface
    {
        switch ($config['driver']) {
            case 'log':
                return new LogMailer($config['log_path']);
            case 'smtp':
                return new SmtpMailer($config['host'], $config['port'], $config['username'], $config['password']);
            default:
                throw new InvalidArgumentException("Unknown mailer driver: ".$config['driver']);
        }
    }
}
