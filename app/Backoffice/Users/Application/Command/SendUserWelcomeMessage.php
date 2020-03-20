<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command;

use Cordo\Core\Application\Queue\AbstractMessage;

class SendUserWelcomeMessage extends AbstractMessage
{
    public string $email;

    public string $locale;
}
