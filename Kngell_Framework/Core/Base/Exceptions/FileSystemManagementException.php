<?php

declare(strict_types=1);

class FileSystemManagementException extends FilesException
{
    public function __construct(string $message, int $code = 0, ?FilesException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}