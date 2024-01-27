<?php

declare(strict_types=1);

class ProductsListPage extends AbstractProductsListPage implements DisplayPagesInterface
{
    public function __construct(array $params, ?MoneyManager $money = null, ?FormBuilder $frm = null, ?ProductsListPaths $paths = null)
    {
        parent::__construct(array_merge($params, [
            'frm' => $frm,
            'paths' => $paths->paths(),
            'money' => $money,
        ]));
    }

    public function displayAll(): mixed
    {
        $addtional_frm = ['product-tag-frm', 'product-variant-frm'];
        return [
            'productList' => $this->productList(),
            'product_form' => $this->productForm(),
            'additionnal_forms' => $this->additionnalForms($addtional_frm),
        ];
    }

    protected function additionnalForms(array $frms = []): string
    {
        $frmHtml = '';
        foreach ($frms as $frm) {
            $frmTemp = '';
            $form = $this->frm->reset()->form([
                'action' => '#',
                'class' => [$frm, 'needs-validation'],
                'id' => $frm,
            ]);
            $frmTemp .= $form->begin();
            $frmTemp .= $form->end();
            $frmHtml .= $frmTemp;
        }
        return $frmHtml;
    }

    protected function productForm(): string
    {
        $frm = $this->frm();
        list($inputs, $obj) = $this->inputHiddenParams();
        $temp = str_replace('{{form_begin}}', $frm->begin(), $this->productFrmtemplate);
        $temp = str_replace('{{form_input_hidden}}', $this->inputHidden($frm, $inputs, $obj), $temp);
        $temp = str_replace('{{product_infos}}', $this->productInfos($frm), $temp);
        $temp = str_replace('{{product_media}}', $this->productMedia(), $temp);
        $temp = str_replace('{{product_pricing}}', $this->productPricing($frm), $temp);
        $temp = str_replace('{{product_inventory}}', $this->productInventory($frm), $temp);
        $temp = str_replace('{{product_shipping}}', $this->productShipping($frm), $temp);
        $temp = str_replace('{{product_variants}}', $this->getTemplate('productVariantsPath'), $temp);
        $temp = str_replace('{{product_organization}}', $this->productOrganization($frm), $temp);
        $temp = str_replace('{{product_categories}}', $this->productCategories($frm), $temp);
        $temp = str_replace('{{product_tags}}', $this->productTags($frm), $temp);
        return str_replace('{{form_end}}', $frm->end(), $temp);
    }

    protected function inputHiddenParams(array $arrInputs = [], ?object $product = null): array
    {
        $arrInputs = empty($arrInput) ? ['operation' => 'add', 'frm_name' => 'new-product-frm'] : $arrInput;
        $obj = is_null($product) ? new stdClass() : $product;
        $inputs = [];
        foreach ($arrInputs as $input => $value) {
            $obj->$input = $value;
            $inputs[] = $input;
        }
        return [$inputs, $obj];
    }

    protected function frm(): FormBuilder
    {
        return $this->frm->form([
            'action' => '#', //DS . 'admin' . DS . 'admin_products' . DS . 'add',
            'class' => ['new-product-frm', 'needs-validation'],
            'id' => 'new-product-frm',
            'enctype' => 'multipart/form-data',
        ])->globalClasses([
            'wrapper' => [],
            'input' => [],
            'label' => [],
        ]);
    }

    protected function productList(): string
    {
        $productsListHtml = '';
        if ($this->products->count() > 0) {
            foreach ($this->products->all() as $product) {
                $temp = str_replace('{{image}}', $this->media($product, 'products/product-80x80.jpg'), $this->template);
                $temp = str_replace('{{title}}', $product->title, $temp);
                $temp = str_replace('{{categorie}}', $product->categorie ?? '', $temp);
                $temp = str_replace('{{price}}', $this->money->getFormatedAmount(strval($product->regularPrice ?? 0)), $temp);
                $temp = str_replace('{{qty}}', strval($product->qty), $temp);
                $temp = str_replace('{{qty_sold}}', strval($product->qty_sold), $temp);
                $temp = str_replace('{{form_edit}}', $this->form('edit', $product->pdtId), $temp);
                $temp = str_replace('{{form_delete}}', $this->form('delete', $product->pdtId), $temp);
                $productsListHtml .= $temp;
            }
        }
        return $productsListHtml;
    }
}
