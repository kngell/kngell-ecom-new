<?php

declare(strict_types=1);

class ModalProductFormAttributes
{
    public function __construct(
        private ?CollectionInterface $productUnits,
        private ?CollectionInterface $backborder,
        private ?CollectionInterface $shippingClass,
        private ?CollectionInterface $company,
        private ?CollectionInterface $warehouse,
        private ?CollectionInterface $categories,
    ) {
    }

        public function merge() : CollectionInterface
        {
            return new Collection([
                /**product_infos */
                'product_infos' => [
                    'text' => [
                        'title' => [
                            'htmlPlace' => 'title',
                            'label' => 'Title:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'short_descr' => [
                            'htmlPlace' => 'short_description',
                            'label' => 'Description courte:',
                            'fieldClass' => ['ck-content'],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                    ],
                    'textarea' => [
                        'descr' => [
                            'htmlPlace' => 'description',
                            'label' => 'Description longue:',
                            'fieldClass' => ['ck-content'],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                    ],

                ],
                /**product_media */
                '$product_media' => [
                    'number' => [
                        'regular_price' => [
                            'htmlPlace' => 'regular_price',
                            'label' => 'Prix:',
                            'fieldClass' => ['regular_price'],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'compare_price' => [
                            'htmlPlace' => 'compare_price',
                            'label' => 'Prix comparé:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'cost_per_item' => [
                            'htmlPlace' => 'cost_per_item',
                            'label' => 'Coût Par Produit:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '<span class="help-block">
                                    <small>Customers won’t see this</small>
                                </span>',
                        ],
                    ],
                    'checkbox' => [
                        'charge_tax' => [
                            'htmlPlace' => 'charge_tax_on_product',
                            'label' => 'Charge tax on this product',
                            'fieldClass' => ['form-check-input'],
                            'labelClass' => ['custom-checkbox'],
                            'closestDivClass' => ['ps-0'],
                            'helpBlock' => '',
                            'checked' => false,
                        ],
                    ],
                ],
                /**product_pricing */
                'product_pricing' => [
                    'number' => [
                        'regular_price' => [
                            'htmlPlace' => 'regular_price',
                            'label' => 'Prix:',
                            'fieldClass' => ['modal_p_regular_price'],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'compare_price' => [
                            'htmlPlace' => 'compare_price',
                            'label' => 'Prix comparé:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'cost_per_item' => [
                            'htmlPlace' => 'cost_per_item',
                            'label' => 'Coût Par Produit:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => ' <span class="help-block">
                            <small>Customers won’t see this</small>
                         </span>',
                        ],
                    ],
                    'checkbox' => [
                        'charge_tax' => [
                            'htmlPlace' => 'charge_tax_on_product',
                            'label' => 'Charge tax on this product',
                            'fieldClass' => ['form-check-input'],
                            'labelClass' => ['custom-checkbox'],
                            'closestDivClass' => ['ps-0'],
                            'helpBlock' => '',
                            'checked' => false,
                        ],
                    ],
                ],
                /**product_inventory */
                'product_inventory' => [
                    'number' => [
                        'sku' => [
                            'htmlPlace' => 'sku_stock',
                            'label' => 'SKU (Stock Keeping Unit):',
                            'fieldClass' => ['modal_p_regular_price'],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'qty' => [
                            'htmlPlace' => 'stock_quantity',
                            'label' => 'Stock quantity:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'stock_threshold' => [
                            'htmlPlace' => 'low_stock',
                            'label' => 'Low stock threshold:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                    ],
                    'checkbox' => [
                        'track_qty' => [
                            'htmlPlace' => 'track_qty',
                            'label' => 'Track quantity',
                            'fieldClass' => ['form-check-input'],
                            'labelClass' => ['custom-checkbox'],
                            'closestDivClass' => ['ps-0'],
                            'helpBlock' => '',
                            'checked' => true,
                        ],
                        'continious_sell' => [
                            'htmlPlace' => 'self_when_out_of_stock',
                            'label' => 'Continue selling when out of stock',
                            'fieldClass' => ['form-check-input'],
                            'labelClass' => ['custom-checkbox'],
                            'closestDivClass' => ['ps-0'],
                            'helpBlock' => '',
                            'checked' => true,
                        ],
                    ],
                    'text' => [
                        'barre_code' => [
                            'htmlPlace' => 'code_bar',
                            'label' => 'Barcode (ISBN, UPC, GTIN, etc.):',
                            'fieldClass' => [],
                            'labelClass' => [],

                            'helpBlock' => '',
                        ],
                    ],
                    'select' => [
                        'back_border' => [
                            'htmlPlace' => 'allow_backorder',
                            'label' => 'Allow backorder:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                            'options' => [
                                'object' => $this->backborder,
                                'id' => 'bb_id',
                                'content' => 'name',
                            ],
                        ],
                        'unit_id' => [
                            'htmlPlace' => 'product_units',
                            'label' => 'Product Unit:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                            'options' => [
                                'object' => $this->productUnits,
                                'id' => 'un_id',
                                'content' => 'unit',
                            ],
                        ],
                    ],
                ],
                /**product_shipping */
                'product_shipping' => [
                    'text' => [
                        'weight' => [
                            'htmlPlace' => 'weight',
                            'label' => 'Weight (kg):',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                    ],
                    'singleInputText' => [
                        'lenght' => [
                            'htmlPlace' => 'length',
                            'placeholder' => 'Length',
                            'formAttr' => [],
                            'label' => '',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'width' => [
                            'htmlPlace' => 'width',
                            'label' => '',
                            'placeholder' => 'Width',
                            'formAttr' => [],
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                        'height' => [
                            'htmlPlace' => 'height',
                            'label' => '',
                            'placeholder' => 'Height',
                            'formAttr' => [],
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                        ],
                    ],
                    'select' => [
                        'shipping_class' => [
                            'htmlPlace' => 'shipping_class',
                            'label' => 'Shipping Class:',
                            'fieldClass' => [],
                            'labelClass' => [],
                            'helpBlock' => '',
                            'options' => [
                                'object' => $this->shippingClass,
                                'id' => 'shc_id',
                                'content' => 'sh_name',
                            ],
                        ],
                    ],
                ],
                /**product_organization */
                'product_organization' => [
                    'select' => [
                        'company' => [
                            'htmlPlace' => 'company',
                            'label' => false,
                            'fieldClass' => ['company'],
                            'labelClass' => [],
                            'wrapperClass' => ['form-group'],
                            'placeholder' => ' ',
                            'helpBlock' => '',
                            'options' => [
                                'object' => $this->company,
                                'id' => 'comp_id',
                                'content' => 'sigle',
                            ],
                        ],
                        'warehouse' => [
                            'htmlPlace' => 'warehouse',
                            'label' => '',
                            'fieldClass' => ['warehouse'],
                            'labelClass' => [],
                            'wrapperClass' => ['form-group'],
                            'formAttr' => ['aria-label' => '.form-select Default', 'multiple' => 'multiple'],
                            'placeholder' => ' ',
                            'helpBlock' => '',
                            'options' => [
                                'object' => $this->warehouse,
                                'id' => 'wh_id',
                                'content' => 'wh_name',
                            ],
                        ],
                    ],
                ],
                /**product_categories */
                'product_categories' => [
                    'checkbox_iterate' => [
                        'categorieItem[]' => [
                            'htmlPlace' => 'categories_items',
                            'labelClass' => ['custom-checkbox'],
                            'closestDivClass' => ['ps-0'],
                            'options' => [
                                'object' => $this->categories,
                                'id' => 'cat_id',
                                'content' => 'categorie',
                            ],
                        ],
                    ],
                ],
                'product_tags' => [
                    'text' => [
                        'tags' => [
                            'htmlPlace' => 'input_tag',
                            'label' => false,
                            'fieldClass' => [],
                            'labelClass' => [],
                            'wrapperClass' => ['form-group', 'me-1', 'flex-grow-1'],
                            'removeWrapperClass' => ['mb-3'],
                            'formAttr' => ['form' => 'product_tag_frm'],
                        ],
                    ],
                ],
            ]);
        }
}