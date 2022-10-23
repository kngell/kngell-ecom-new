<?php

declare(strict_types=1);
class AdminProductsController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    public function add(array $args = []) : void
    {
        $data = $this->isValidRequest();
        /** @var ProductsManager */
        $model = $this->model(ProductsManager::class)->assign($data)
            ->uploadFiles(IMAGE_ROOT);
        $this->isIncommingDataValid(m: $model, ruleMethod:'products');
        if ($resp = $model->save()) {
            $this->dispatcher->dispatch(new AddNewProductEvent($this, '', [
                'data' => $data,
                'cache' => $this->cachedFiles['products_list'],
            ]), $resp);
        }
    }
}