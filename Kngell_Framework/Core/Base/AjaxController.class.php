<?php

declare(strict_types=1);
class AjaxController extends Controller
{
    protected function isValidRequest(?string $csrfName = null) : bool|array
    {
        $data = $this->request->get();
        if ($data['csrftoken'] && $this->token->validate($data['csrftoken'], $csrfName ?? $data['frm_name'])) {
            return $data;
        }
        if ($this->request->isAjax()) {
            $this->jsonResponse(['result' => 'error', 'msg' => 'redirect']);
        } else {
            header('location :' . $this->request->pageUrl());
        }
        return false;
        //throw new BaseException('Veuillez rafraichir la page svp!');
    }

    protected function jsonResponse(array $resp) : void
    {
        $this->response->jsonResponse($resp);
    }
}