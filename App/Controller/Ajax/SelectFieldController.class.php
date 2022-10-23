<?php

declare(strict_types=1);

class SelectFieldController extends Controller
{
    public function all(array $args = [])
    {
        $data = $this->isValidRequest();
        if (isset($data['table'])) {
            $modelName = ucfirst(StringUtil::camelCase($data['table'])) . 'Manager';
            $results = $this->model($modelName)->assign($data)->select2Data();
            $this->jsonResponse(['result' => 'success', 'msg' => $results]);
        }
    }
}