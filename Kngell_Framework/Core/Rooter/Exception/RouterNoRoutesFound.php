<?php

declare(strict_types=1);

class RouterNoRoutesFound extends Exception //BaseResourceNotFoundException
{
    private $statusCode = 400;

    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the value of statusCode.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the value of statusCode.
     */
    public function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}