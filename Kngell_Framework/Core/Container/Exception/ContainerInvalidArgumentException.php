<?php

use Psr\Container\ContainerExceptionInterface;

declare(strict_types=1);

/** PSR-11 Container */
class ContainerInvalidArgumentException extends BaseInvalidArgumentException implements ContainerExceptionInterface
{
}