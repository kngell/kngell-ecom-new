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

    public function isVisitorNeedUpdate(Object $visitor) : bool
    {
        $update = false;
        if ($this->entity instanceof VisitorsEntity && $this->entity->isPropertiesSet()) {
            $attrs = $this->entity->getInitializedAttributes();
            foreach ($attrs as $key => $value) {
                if (in_array($key, array_keys((array) $visitor))) {
                    if ($key != 'updatedAt' && $visitor->$key != $value) {
                        $update = true;
                        break;
                    }
                } else {
                    $update = true;
                    break;
                }
            }
        }
        return $update;
    }

    public function manageVisitors(VisitorsFromCache $cVisitors) : Entity
    {
        $visitors = $cVisitors->get();
        [$uniqueV,$duplicatesV,$isUniqueV] = $this->isVisitorUnique($visitors, 'ipAddress');
        if (! $isUniqueV) {
            $delete = $this->deleteDuplicatesVisitors($duplicatesV, $cVisitors);
        }
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $cookie = $this->cookie->get(VISITOR_COOKIE_NAME);
            [$visitor,$vExists] = $this->isVisitorExists($uniqueV, $cookie);
            if (! $vExists) {
                return $this->addNewVisitor($cookie);
            }
            $update = $this->isVisitorNeedUpdate($visitor);
            if ($update) {
                return $this->updateVisitor();
            }
            return $this->entity->assign((array) $visitor);
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

    public function getAllVisitors() : CollectionInterface
    {
        $this->query()->return('object');
        return new Collection($this->getAll()->get_results());
    }

    public function getVisitorByIp() : ?Entity
    {
        $this->query()->where(['ipAddress' => H_visitors::getIP()])->return('class');
        $v = $this->getAll();
        if ($v->count() >= 1) {
            return current($v->get_results());
        }
        return null;
    }

    private function isVisitorExists(array $visitors, string $cookie) : array
    {
        foreach ($visitors as $visitor) {
            if ($visitor->cookies === $cookie) {
                return [$visitor, true];
            }
        }
        return [[], false];
    }

    private function deleteDuplicatesVisitors(array $duplicatesV, VisitorsFromCache $cVisitors)
    {
        $this->query()->delete()->whereIn('vId', $duplicatesV)->go();
        $delete = $this->delete();
        if ($delete) {
            $cachedFile = $cVisitors->getCachedFiles()['visitors'];
            $this->cache->delete($cachedFile);
        }
        return $delete;
    }

    private function isVisitorUnique(CollectionInterface $visitors, string $key) : array
    {
        $arrVisitors = $visitors->all();
        $unique = true;
        list($uniqueV, $duplicatesV) = ArrayUtil::uniqueObjectByKey($arrVisitors, $key);
        if (! empty($duplicatesV)) {
            $unique = false;
        }
        return [$uniqueV, $duplicatesV, $unique];
    }

    private function updateVisitor() : self
    {
        if (! $update = $this->update()) {
            throw new BaseRuntimeException('Erreur lors de la mise à jour des données visiteur!');
        }
        return $update ?? null;
    }
}