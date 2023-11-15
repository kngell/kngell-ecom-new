<?php

declare(strict_types=1);
class ProductsManager extends Model
{
    protected string $_colID = 'pdt_id';
    protected string $_table = 'products';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getProducts(int|string|null $brand = null) : CollectionInterface
    {
        if ($brand === null) {
            $this->table()
                ->leftJoin('product_categorie', ['pdt_id', 'cat_id'])
                ->leftJoin('categories', ['categorie'])
                ->leftJoin('brand', ['br_name'])
                ->leftJoin('order_details', ['COUNT|od_quantity|qty_sold'])
                ->on(['pdt_id',  'pdt_id'], ['cat_id', 'cat_id'], ['br_id', 'br_id'], ['pdt_id|products', 'od_product_id'])
                ->groupBy(['pdt_id DESC' => 'product_categorie'])
                ->return('object');
        } else {
            $this->table()
                ->leftJoin('product_categorie', ['pdt_id', 'cat_id'])
                ->leftJoin('categories', ['categorie'])
                ->leftJoin('brand', ['br_name'])
                ->on(['pdt_id',  'pdt_id'], ['cat_id', 'cat_id'], ['br_id', 'br_id'])
                ->where(['br_id' => $brand . '|categories'])
                ->groupBy(['pdt_id DESC' => 'product_categorie'])
                ->return('object');
        }
        return new Collection($this->getAll()->get_results());
    }

    public function getSingleProduct(string $slug) : ?object
    {
        $this->table()
            ->leftJoin('product_categorie', ['pdt_id', 'cat_id'])
            ->leftJoin('categories', ['categorie'])
            ->leftJoin('brand', ['br_name'])
            ->on(
                ['pdt_id',  'pdt_id'],
                ['cat_id', 'cat_id'],
                ['br_id', 'br_id'],
            )
            ->where(['slug' => $slug . '|products'])
            ->groupBy(['pdt_id DESC' => 'products'])
            ->return('object');
        $pdt = $this->getAll();
        if ($pdt->count() === 1) {
            return current($pdt->get_results());
        }
        return null;
    }

    public function getEditedProduct(null|int $id = null) : ?self
    {
        $id = $this->id($id);
        $this->table()
            ->leftJoin('product_categorie', ['pdt_id', 'cat_id'])
            ->leftJoin('categories', ['categorie'])
            ->leftJoin('brand', ['br_name'])
            ->leftJoin('warehouse_product', ['product_id', 'wh_id'])
            ->leftJoin('warehouse', ['wh_id', 'wh_name'])
            ->leftJoin('company', ['comp_id', 'sigle'])
            ->leftJoin('shipping_class', ['shc_id', 'sh_name'])
            ->leftJoin('units', ['un_id', 'unit'])
            ->leftJoin('back_border', ['bb_id', 'name'])
            ->on(
                ['pdt_id',  'pdt_id'],
                ['cat_id', 'cat_id'],
                ['br_id', 'br_id'],
                ['pdt_id|products', 'product_id|warehouse_product'],
                ['wh_id|warehouse_product', 'wh_id|warehouse'],
                ['company|products', 'comp_id|company'],
                ['shipping_class|products', 'shc_id|shipping_class'],
                ['unit_id|products', 'un_id|units'],
                ['back_border|products', 'bb_id|back_border']
            )
            ->where(['pdt_id' => $id . '|products'])
            ->return('object');
        return $this->getAll();
    }

    public function beforeSave(null|Entity|CollectionInterface $entity = null) : mixed
    {
        /** @var ProductsEntity */
        $en = parent::beforeSave($entity);
        // Manage prices
        $en->setRegularPrice($this->money->persistPrice($en->getRegularPrice()));
        $en->setComparePrice($this->money->persistPrice($en->getComparePrice()));
        $en->setCostPerItem($this->money->persistPrice($en->getCostPerItem()));
        $en->setShippingClass($this->defaultShippingClass());
        // User Salt
        /** @var UsersEntity */
        $user = AuthManager::currentUser()->getEntity();
        $en->setUserSalt($user->getSalt());
        // product slag
        $en->setSlug($this->getSlug($en));
        return $en;
    }

    private function id(null|int $id = null) : int
    {
        if($id === null) {
            $en = $this->getEntity();
            return $en->{$en->getGetters($en->getColId())}();
        }
        return $id;
    }

    private function getSlug(object $en) : string
    {
        $slug = StringUtil::strToUrl($en->getTitle());
        if (!$en->isInitialized('slug')) {
            while ((new self)->getDetails($slug, 'slug')->count() > 0) :
                $slug = StringUtil::strToUrl($slug . '-' . rand(0, 99999));
            endwhile;
        }
        return $slug;
    }
}