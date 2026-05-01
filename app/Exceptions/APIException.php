<?php

namespace App\Exceptions;

use Exception;

abstract class APIException extends Exception
{
    protected int $httpCode;

    protected string $customMessage;

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getCustomMessage(): string
    {
        return $this->customMessage;
    }
}
