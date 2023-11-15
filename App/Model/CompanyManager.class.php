<?php

declare(strict_types=1);
class CompanyManager extends Model
{
    protected string $_colID = 'comp_id';
    protected string $_table = 'company';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}