<?php

declare(strict_types=1);
class CategoriesManager extends Model
{
    protected string $_colID = 'cat_id';
    protected string $_table = 'categories';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}