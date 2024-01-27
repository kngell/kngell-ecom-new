<?php

declare(strict_types=1);

abstract class AbstractProductsListPage
{
    use DisplayTraits;
    use DisplayFormElementTrait;
    protected string $template;
    protected string $frmTemplate;
    protected string $productFrmtemplate;
    protected string $categorieItemTemplate;
    // protected array $select2Fields = [];
    protected ?CollectionInterface $paths;
    protected ?CollectionInterface $products;
    protected ?CollectionInterface $productUnits;
    protected ?CollectionInterface $backborder;
    protected ?CollectionInterface $shippingClass;
    protected ?CollectionInterface $company;
    protected ?CollectionInterface $warehouse;
    protected ?CollectionInterface $categories;
    protected ?MoneyManager $money;
    protected ?FormBuilder $frm;
    protected ?CollectionInterface $arrInputs;

    public function __construct(array $params)
    {
        $this->properties($params);
        $this->template = $this->getTemplate('productsListPaths');
        $this->frmTemplate = $this->getTemplate('productsfrmPaths');
        $this->productFrmtemplate = $this->getTemplate('productformPath');
        $this->categorieItemTemplate = $this->getTemplate('categorieItemPath');
        $this->arrInputs = (new ModalProductFormAttributes(
            $this->productUnits,
            $this->backborder,
            $this->shippingClass,
            $this->company,
            $this->warehouse,
            $this->categories
        ))->merge();
        // $this->select2Fields = $this->select2Fields;
    }

    protected function form(?string $type = null, int|string|null $id = null) : string
    {
        $frm = $this->frm->form([
            'action' => '#',
            'class' => [$type . '-frm'],
            'id' => $type . '-frm' . $id,
        ])->globalClasses([
            'wrapper' => [],
            'input' => [],
            'label' => [],
        ]);
        $template = str_replace('{{form_begin}}', $frm->begin(), $this->frmTemplate);
        $template = str_replace('{{input}}', $frm->input([
            HiddenType::class => ['name' => 'pdtId'],
        ])->value($id)->noLabel()->noWrapper()->html(), $template);
        list($btnContent, $btnAttr, $btnType) = $this->btnContent($type);
        $template = str_replace('{{button}}', $this->frm->input([
            ButtonType::class => ['type' => $btnType, 'class' => ['editBtn']],
        ])->content($btnContent)->attr($btnAttr)->html(), $template);
        return str_replace('{{form_end}}', $frm->end(), $template);
    }

    protected function productInfos(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('productInfoPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productMedia() : string
    {
        return str_replace('{{dragAndDrop}}', $this->getTemplate('dragandDroppath'), $this->getTemplate('productMediaPath'));
    }

    protected function productPricing(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('productPricingPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productInventory(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('productInventoryPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productShipping(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('productShippingPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productVariants(FormBuilder $frm, ?object $product = null) : string
    {
        $arrInputs = [
            'singleInputText' => [
                'lenght' => [
                    'htmlPlace' => 'length',
                    'placeholder' => 'Length',
                    'formAttr' => ['form' => 'product-variant-frm'],
                    'label' => '',
                    'fieldClass' => [],
                    'labelClass' => [],
                    'id' => 'modal_p_lenght',
                    'helpBlock' => '',
                ],
                'width' => [
                    'htmlPlace' => 'width',
                    'label' => '',
                    'placeholder' => 'Width',
                    'fieldClass' => [],
                    'labelClass' => [],
                    'id' => 'modal_p_width',
                    'helpBlock' => '',
                ],
                'height' => [
                    'htmlPlace' => 'height',
                    'label' => '',
                    'placeholder' => 'Height',
                    'fieldClass' => [],
                    'labelClass' => [],
                    'id' => 'modal_p_height',
                    'helpBlock' => '',
                ],
            ],
            'select' => [
                'shipping_class' => [
                    'htmlPlace' => 'shipping_class',
                    'label' => 'Shipping Class:',
                    'fieldClass' => [],
                    'labelClass' => [],
                    'id' => 'modal_p_shipping_class',
                    'helpBlock' => '',
                    'options' => [
                        'object' => $this->shippingClass,
                        'id' => 'shc_id',
                        'content' => 'sh_name',
                    ],
                ],
            ],
        ];
        return $this->inputs($this->getTemplate('productVariantsPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productOrganization(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('productOrgaPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productCategories(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('categoriePath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    protected function productTags(FormBuilder $frm, ?object $product = null) : string
    {
        return $this->inputs($this->getTemplate('productTagsPath'), $frm, $product, $this->arrInputs->get(StringUtil::separate(__FUNCTION__)));
    }

    private function btnContent(string $type) : array
    {
        return match ($type) {
            'edit' => [
                '<i class="fa-solid fa-pen" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"></i>', [
                    'title' => 'Edit Product',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#modal-box',
                ],
                'button',
            ],
            'delete' => ['<i class="fa-regular fa-trash-can" data-bs-original-title="Archive" data-bs-toggle="tooltip"></i>', [
                'title' => 'Delete Product',
            ],
                'submit',
            ]
        };
    }
}
