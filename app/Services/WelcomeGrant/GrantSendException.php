<?php

namespace App\Services\WelcomeGrant;

use RuntimeException;

class GrantSendException extends RuntimeException
{
    /**
     * @param  bool  $nothingSent  true only when it is certain that no coins left the wallet
     */
    public function __construct(string $message, public readonly bool $nothingSent)
    {
        parent::__construct($message);
    }
}
