<?php

declare(strict_types=1);

class VisitorsController extends AjaxController
{
    private VisitorsFromCache $visitors;

    public function __construct(?VisitorsFromCache $visitiors = null)
    {
        $this->visitors = $visitiors;
    }

    public function track()
    {
        $data = $this->isValidRequest();
        /** @var VisitorsManager */
        $model = $this->model(VisitorsManager::class)->assign($data)
            ->manageVisitors($this->visitors);
        if (! $model || ($model->count() > 0)) {
            $this->jsonResponse(['result' => 'success', 'msg' => is_bool($model) ? $model : $model->get_results()]);
        }
    }

    // public function saveipdata()
    // {
    //     if ($this->request->exists('post')) {
    //         $data = $this->helper->transform_keys($this->request->get(), H_visitors::new_IpAPI_keys());
    //         $this->assign($data);
    //         if (isset($data['ipAddress']) && ! $this->model_instance->getByIp($data['ipAddress'])) {
    //             $this->model_instance->save();
    //         }
    //     }
    // }
}