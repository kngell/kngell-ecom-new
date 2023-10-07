<?php

declare(strict_types=1);

class EditProductPage extends AbstractEditProductPage implements DisplayPagesInterface
{
    public function __construct(ProductsManager $product)
    {
        parent::__construct($product);
    }

     public function displayAll(): mixed
     {
         $product_branch = $this->branch();
         if (isset($product_branch->media) && empty(unserialize($product_branch->media))) {
             unset($product_branch->media);
         } else {
             $product_branch->media = $this->media($product_branch, null, false);
         }
         return [
             'items' => $product_branch,
             'input_hidden' => ['pdt_id', 'user_salt', 'slug', 'created_at', 'updated_at', 'deleted'],
             'selectedOptions' => $this->selectedOptions(),
         ];
     }

     protected function selectedOptions() : array
     {
         return [
             'categorie' => $this->categories(),
             'select_field' => $this->selectFields(),
         ];
     }
}