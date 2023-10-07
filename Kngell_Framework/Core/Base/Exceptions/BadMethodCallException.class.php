<?php

declare(strict_types=1);

class BadMethodCallException extends BaseResourceNotFoundException
{
    /**
     * BadMethodCallException.
     *
     * @param string $message
     * @param int $code
     * @param self|null $previous
     */
    public function __construct(string $message, int $code = 0, ?self $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}