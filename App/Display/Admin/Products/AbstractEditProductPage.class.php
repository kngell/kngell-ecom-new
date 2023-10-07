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

   protected function unique_values($array, $keep_key_assoc = false)
   {
       $duplicate_keys = [];
       $tmp = [];
       foreach ($array as $key => $val) {
           // convert objects to arrays, in_array() does not support objects
           if (is_object($val)) {
               $val = (array) $val;
           }
           if (!in_array($val, $tmp)) {
               $tmp[] = $val;
           } else {
               $duplicate_keys[] = $key;
           }
       }
       foreach ($duplicate_keys as $key) {
           unset($array[$key]);
       }
       return $keep_key_assoc ? $array : array_values($array);
   }

   protected function productField(string $field, string $text, bool $multiple = false) : array
   {
       $fields = [];
       $result = $this->product->get_results();
       foreach ($result as $key => $value) {
           if ($value->$field !== '' && $value->$text !== null) {
               if ($multiple == false) {
                   $fields[$value->$field] = $value->$text;
               } elseif ($multiple == true) {
                   $fields[][$value->$field] = $value->$text;
               }
           }
       }
       $fields = $this->unique_values($fields, true);
       if ($multiple == false) {
           return !empty($fields) ? ['id' => key($fields), 'text' => StringUtil::htmlDecode($fields[key($fields)])] : [];
       }
       $resp = [];
       foreach ($fields as $key => $field) {
           $resp[] = !empty($field) ? ['id' => key($field), 'text' => StringUtil::htmlDecode($field[key($field)])] : [];
       }
       return $resp;
   }

    protected function branch() : object
    {
        $result = $this->product->get_results()[0];
        $en = $this->product->assign((array) $result)->getEntity()->getInitializedAttributes();
        return (object) $en;
    }

    protected function selectFields() : array
    {
        $selects = [];
        $select2Fields = YamlFile::get('select2Field')['admin'];
        foreach ($select2Fields as $selectField) {
            foreach ($selectField as $field => $params) {
                $true = false;
                if ($params['type'] === 'multiple') {
                    $true = true;
                }
                $selects[$field] = $this->productField($params['attrs']['id'], $params['attrs']['name'], $true);
            }
        }
        return $selects;
        // return [
        //     'company' => $this->productField('comp_id', 'sigle'),
        //     'warehouse' => $this->productField('wh_id', 'wh_name', true),
        //     'shipping_class' => $this->productField('shc_id', 'sh_name'),
        //     'unit_id' => $this->productField('un_id', 'unit'),
        //     'back_border' => $this->productField('bb_id', 'name'),
        // ];
    }
}