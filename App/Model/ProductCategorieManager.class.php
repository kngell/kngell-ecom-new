<?php

declare(strict_types=1);
class ProductCategorieManager extends Model
{
    protected string $_colID = 'pc_id';
    protected string $_table = 'product_categorie';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}