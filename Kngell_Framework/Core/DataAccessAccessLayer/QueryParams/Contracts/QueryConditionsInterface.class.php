<?php

declare(strict_types=1);
interface QueryConditionsInterface
{
    public function where(...$conditions) : self;

    public function orWhere(...$conditions) : self;

    public function andWhere(...$conditions) : self;

    public function on(...$onConditions) : self;

    public function having(...$havingConditions) : self;
}