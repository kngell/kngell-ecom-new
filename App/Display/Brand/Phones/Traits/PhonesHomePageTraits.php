<?php

declare(strict_types=1);

trait PhonesHomePageTraits
{
    protected function outputProduct(string $template, ?object $product = null) : string
    {
        if (($media = $this->media($product, null, true)) !== '') {
            $product->userCart = $this->userCart->getUserCart();
            $template = str_replace('{{route}}', $this->detailsRoute($product), $template);
            $template = str_replace('{{image}}', $media, $template);
            $template = str_replace('{{title}}', $product->title ?? 'Unknown', $template);
            $template = str_replace('{{price}}', $this->money->getFormatedAmount(strval($product->regularPrice)), $template);
            $template = str_replace('{{ProductForm}}', $this->productForm($product), $template);
            return str_replace('{{brandClass}}', $product->categorie ?? 'Brand', $template);
        }
        return '';
    }

    protected function productForm(object $product, ?string $title = null) : string
    {
        $formClass = 'add_to_cart_frm';
        if ($title != null && $title == 'Proceed to buy') {
            $formClass = 'proceed_to_buy';
        }
        $form = $this->frm->form([
            'action' => '',
            'class' => [$formClass],
        ])->setCsrfKey($formClass . $product->pdtId ?? 1);

        list($class, $title) = $this->producttitleAndClass($product, $title);
        $template = $this->getTemplate('productFormPath');
        $template = str_replace('{{form_begin}}', $form->begin(), $template);
        $template = str_replace('{{item}}', $form->input([
            HiddenType::class => ['name' => 'itemId'],
        ])->noLabel()->noWrapper()->value($product->pdtId)->html(), $template);
        $template = str_replace('{{user}}', $form->input([
            HiddenType::class => ['name' => 'userId'],
        ])->noLabel()->noWrapper()->value('1')->html(), $template);
        $template = str_replace('{{button}}', $form->input([
            ButtonType::class => ['type' => 'submit', 'class' => $class],
        ])->content($title)->noWrapper()->html(), $template);
        return str_replace('{{form_end}}', $form->end(), $template);
    }

    private function detailsRoute(object $product) : string
    {
        if (isset($product->slug)) {
            return $this->simpleProductRoute . $product->slug;
        }
        return '';
    }

    private function producttitleAndClass(?object $dataRepository = null, ?string $title = null) : array
    {
        /** @var CollectionInterface */
        $userCart = $dataRepository->userCart;
        $cartKeys = $userCart->map(function ($item) {
            if ($item->cartType == 'cart') {
                return $item->itemId;
            }
        })->all();
        if ($title !== null && $title == 'Proceed to buy') {
            $class = ['btn', 'btn-danger', 'font-size-14', 'form-control'];
        } elseif ($title !== null && $title == 'Add to Cart') {
            $class = ['btn', 'font-size-14', 'form-control'];
        }
        if (isset($cartKeys) && in_array($dataRepository->pdtId, $cartKeys)) {
            if (isset($class) && $title == 'Add to Cart') {
                $class = array_merge($class, ['btn-success']);
                $title = 'In the Cart';
            }

            return [$class ?? ['btn', 'btn-success', 'font-size-12'], $title == null ? 'In the Cart' : $title];
        }
        if (isset($class) && $title == 'Add to Cart') {
            $class = array_merge($class, ['btn-warning']);
        }

        return [$class ?? ['btn', 'btn-warning', 'font-size-12'], $title == null ? 'Add to Cart' : $title];
    }
}
