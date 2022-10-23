<?php

declare(strict_types=1);

abstract class AbstractEditProductPage
{
    use DisplayTraits;

    protected ?ProductsManager $product = null;

    public function __construct(ProductsManager $product)
    {
        $this->product = $product;
    }

   protected function categories() : array
   {
       $categories = [];
       $result = $this->product->get_results();
       foreach ($result as $key => $value) {
           $categories[$value->cat_id] = $value->categorie;
       }
       return $categories;
   }

    protected function branch() : object
    {
        $result = $this->product->get_results()[0];
        $en = $this->product->assign((array) $result)->getEntity()->getInitializedAttributes();
        return (object) $en;
    }
}