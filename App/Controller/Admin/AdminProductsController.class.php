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
        $data = $this->selectFields($this->isValidRequest());
        /** @var ProductsManager */
        $model = $this->model(ProductsManager::class)->assign($data)
            ->uploadFiles(IMAGE_ROOT);
        $this->isIncommingDataValid(m: $model, ruleMethod:'products');
        if ($resp = $model->save()) {
            $this->dispatcher->dispatch(new AddNewProductEvent($this, '', [
                'data' => $data,
                'cache' => $this->session->exists(ACTIVE_CACHE_FILES) ? $this->session->get(ACTIVE_CACHE_FILES) : [],
            ]), $resp);
        }
    }

    public function selectFields(array $data = []) : array
    {
        $s2fields = YamlFile::get('select2Field')['admin'];
        foreach ($s2fields as $fields) {
            foreach ($fields as $key => $s2Params) {
                if (array_key_exists($key, $data)) {
                    $attrs = json_decode(StringUtil::htmlDecode($data[$key]));
                    if (isset($attrs)) {
                        if (empty($attrs)) {
                            $data[$key] = $attrs;
                        } else {
                            $data[$key] = count($attrs) > 1 ? array_column($attrs, 'id') : $attrs[0]->id;
                        }
                    } else {
                    }
                }
            }
        }
        return $data;
    }
}