<?php

declare(strict_types=1);

class TaxesManager extends Model
{
    protected string $_colID = 't_id';
    protected string $_table = 'taxes';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getTaxSystem(array $categories = []) : CollectionInterface
    {
        $this->query()->join('taxe_region', ['tr_catID', 'tr_tax_id'])
            ->on([$this->_colID, 'tr_tax_id'])
            ->whereIn('taxe_region|tr_catID', $categories)
            ->groupBy('tr_tax_id')
            ->return('object');
        return new Collection($this->getAll()->get_results());
    }
}