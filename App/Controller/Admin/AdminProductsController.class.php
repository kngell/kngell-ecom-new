<?php

declare(strict_types=1);
class AdminProductsController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    public function add(array $args = [])
    {
        $data = $this->isValidRequest();
        /** @var ProductsManager */
        $model = $this->model(ProductsManager::class)->assign($data)->uploadFiles(IMAGE_ROOT);
        $files = $this->request->getHttpFiles();
        return $this;
    }
}