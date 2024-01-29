<?php

declare(strict_types=1);

interface QueryStatementInterface
{
    public function proceed() : array;
}