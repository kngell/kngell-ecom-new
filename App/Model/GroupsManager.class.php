<?php

declare(strict_types=1);
class GroupsMaganer extends Model
{
    protected string $_colID = 'grId';
    protected string $_table = 'groups';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}