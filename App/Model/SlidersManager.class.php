<?php

declare(strict_types=1);
class SlidersManager extends Model
{
    protected string $_colID = 'sl_id';
    protected string $_table = 'sliders';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function all() : CollectionInterface
    {
        $this->query()->return('object');
        return new Collection($this->getAll()->get_results());
    }
}