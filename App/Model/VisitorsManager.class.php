<?php

declare(strict_types=1);
class VisitorsManager extends Model
{
    protected string $_table = 'visitors';
    protected string $_colID = 'vID';
    protected string $_colIndex = 'cookies';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function assign(array|bool $data): self
    {
        if ($data) {
            $data = $this->helper->transform_keys($data, H_visitors::new_IpAPI_keys());
        }
        parent::assign($data);
        return $this;
    }

    public function manageVisitors(array $params = []) : self
    {
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $visitorInfos = $this->getVisitorInfos($params['ip'] ?? '91.173.88.22');
            return match ($visitorInfos->count()) {
                0 => $this->addNewVisitor($this->cookie->get(VISITOR_COOKIE_NAME)),
                1 => $this->updateVisitorInfos(),
                default => $this->deleteVisitor()
            };
        } else {
            /** @var VisitorsEntity */
            $entity = $this->getVisitorByIp();
            if ($entity !== null) {
                $this->cookie->set($entity->getCookies(), VISITOR_COOKIE_NAME);
                $this->entity = $entity;
                return $this;
            } else {
                return $this->addNewVisitor();
            }
        }
    }

    //Add new visitor
    public function addNewVisitor(?string $cookies = null) : self|bool
    {
        $newV = false;
        if ($cookies == null) {
            $cookies = $this->getUniqueID('cookies');
            $newV = true;
        }
        $this->assign([
            'cookies' => $cookies,
            'useragent' => Session::uagent_no_version(),
            'hits' => 1,
        ]);

        if ($save = $this->save()) {
            $newV ? $this->cookie->set($cookies) : '';
            return $save;
        }
        return false;
    }

    public function getEntity() : VisitorsEntity
    {
        return parent::getEntity();
    }

    public function getAllVisitors() : CollectionInterface
    {
        $this->table()->return('object');
        return new Collection($this->getAll()->get_results());
    }

    public function getVisitorByIp() : ?Entity
    {
        $this->table()->where([
            'ipAddress|in' => [[H_visitors::getIP(), '2', '3'], 'visitors'],
        ])->return('class');
        $v = $this->getAll();
        if ($v->count() > 1) {
            return current($v->get_results());
        }
        return null;
    }

    private function getIpData() : mixed
    {
        if ($this->entity instanceof VisitorsEntity) {
            $ip = $this->entity->isInitialized('ipAddress') ? $this->entity->getIpAddress() : H_visitors::getIP();
            if ($ip == '::1') {
                $ip = '91.173.88.22';
            }
            return H_visitors::getIpData($ip);
        }
    }

    private function getVisitorInfos(string $ip) : self
    {
        $query_data = $this->table()->where([
            'cookies' => $this->cookie->get(VISITOR_COOKIE_NAME),
            'ipAddress|in' => [[$ip, '2', '3'], 'visitors'],
        ])
            ->return('class')
            ->build();
        return $this->getAll($query_data);
    }

    private function updateVisitorInfos() : self
    {
        // /** @var VisitorsEntity */
        // $oldVisitor = current($m->get_results());
        // $this->entity->assign(array_merge($this->helper->transform_keys(! is_array($ipData) ? ['ipAddress' => $ipData] : $ipData, H_visitors::new_IpAPI_keys()), (array) $this->entity));
        if (! $update = $this->update()) {
            throw new BaseRuntimeException('Erreur lors de la mise à jour des données visiteur!');
        }
        return $update ?? null;
    }

    private function deleteVisitor() : self
    {
        // $vInfos = $m->get_results();
        // if (count($vInfos) > 1) {
        //     foreach ($vInfos as $info) {
        //         $info->assign((array) $info);
        //         $info->getQueryParams()->reset();
        if (! $delete = $this->delete()) {
            throw new BaseRuntimeException('Erreur lors de la mise à jour des données visiteur!');
        }
        // }

        return $delete; //$this->addNewVisitor();
        // }
    }
}