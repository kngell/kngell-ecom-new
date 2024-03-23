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
        $this->query()->select()->join('taxe_region', ['trCatID', 'trTaxId'])
            ->on(['taxes|' . $this->_colID, 'taxe_region|trTaxId'])
            ->whereIn('taxe_region|trCatID', $categories)
            ->groupBy('trTaxId')
            ->return('object');
        return new Collection($this->getAll()->get_results());
    }
}