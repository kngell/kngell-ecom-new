<?php

declare(strict_types=1);

class ShippingClassManager extends Model
{
    protected string $_colID = 'shc_id';
    protected string $_table = 'shipping_class';
    protected string $_colIndex = 'sh_comp_id';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getShippingClass() : CollectionInterface
    {
        $this->table()
            ->return('object');
        return new Collection($this->getAll()->get_results());
    }

    public function getDefault() : ?int
    {
        $this->table()->where(['default_shipping_class|<>' => 0])->return('object');
        $sh = $this->getAll();
        if ($sh->count() > 0) {
            return current($sh->get_results())->shc_id;
        }
        return null;
    }
}