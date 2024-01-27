<?php

declare(strict_types=1);

abstract class AbstractStatementParams extends AbstractQueryStatement
{
    abstract public function proceed() : array;
}