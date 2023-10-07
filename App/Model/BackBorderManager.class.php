<?php

declare(strict_types=1);
class BackBorderManager extends Model
{
    protected string $_colID = 'bb_id';
    protected string $_table = 'back_border';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}