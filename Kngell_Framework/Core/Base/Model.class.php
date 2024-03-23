<?php

declare(strict_types=1);
class Model extends AbstractModel
{
    use ModelTrait;

    /**
     * Main Constructor
     * =======================================================================.
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $tableSchema, string $tableSchemaID)
    {
        parent::__construct($tableSchema, $tableSchemaID);
    }

    public function getAll() : ?self
    {
        return $this->find();
    }

    public function getDetails(...$params) : ?self
    {
        $condition = [];
        if(is_array($params) && count($params) == 1) {
            $params = $params[0];
        }
        if(ArrayUtil::isAssoc($params) && count($params) > 1) {
            throw new BadQueryArgumentException('Please provide only one condition');
        }
        if(ArrayUtil::is_sequential_array($params)) {
            if(count($params) == 1) {
                $condition = [$this->tableSchemaID => $params[0]];
            } elseif(count($params) == 2) {
                $condition = [$params[0] => $params[1]];
            } elseif(count($params) == 3) {
                $condition = [$params[0], $params[1], $params[2]];
            } else {
                throw new BadQueryArgumentException('Please provide field/value list for one condition only');
            }
        }
        if(empty($condition)) {
            $this->query()->select()->return('class');
        } else {
            $this->query()->where($condition)->return('class');
        }
        // if (null == $colID) {
        //     $colId = $this->tableSchemaID;
        //     throw new BaseException("Impossible de trouver l'enregistrement souhaité");
        // }
        // $data_query = $this->query()
        //     ->where([$colID != '' ? $colID : $this->tableSchemaID => $id])
        //     ->return($mode);

        return $this->find();
    }

    public function save(null|Entity|CollectionInterface $entity = null): ?self
    {
        $en = $this->beforeSave($entity);
        if ($en->isInitialized($en->getColId())) {
            $en = $this->beforeSaveUpadate($en);
            $save = $this->update($en);
        } else {
            $en = $this->beforeSaveInsert($en);
            $save = $this->insert($en);
        }
        if ($save->count() > 0) {
            $params['saveID'] = $save ?? '';
            return $this->afterSave($params);
        }
        return null;
    }

    public function insert(null|Entity $entity = null) : self
    {
        null == $entity ? $this->entity = $entity : '';
        $result = $this->repository()->create();
        $this->setAllReturnedValues($result);
        return $this;
    }

    public function update(null|Entity $entity = null) : self
    {
        $entity = null == $entity ? $entity : $this->entity;
        if(! CustomReflector::getInstance()->isInitialized('queryType', $this->queryParams)) {
            $this->query()->update($entity)->go();
        }
        $result = $this->repository()->update();
        $this->setAllReturnedValues($result);
        return $this;
    }

    public function delete(null|Entity $entity = null) : ?self
    {
        $entity = $this->beforeDelete($entity);
        $result = $this->repository()->delete();
        $result = $this->afterDelete($result);
        $this->setAllReturnedValues($result);
        return $this;
    }

    public function count() : int
    {
        return $this->_count;
    }

    public function assign(array|bool $data) : self
    {
        if ($data) {
            $this->entity->assign($data);
        }
        return $this;
    }

    public function getUniqueId(string $colid_name = '', string $prefix = '', string $suffix = '', int $token_length = 24) : mixed
    {
        $output = $prefix . $this->token->generate($token_length) . $suffix;
        while ($this->getDetails($output, $colid_name)->count() > 0) :
            $output = $prefix . $this->token->generate($token_length) . $suffix;
        endwhile;

        return $output;
    }

    private function beforeDelete(?Entity $entity = null) : Entity|bool
    {
        if(CustomReflector::getInstance()->isInitialized('queryType', $this->queryParams) && $this->queryParams->getQueryType()->name == 'DELETE') {
            return true;
        }
        $entity = null != $entity ? $entity : $this->entity;
        $this->query()->delete($entity)->go();
        return $entity;
    }

    //After delete
    private function afterDelete(DataMapperInterface $resp) : DataMapperInterface
    {
        return $resp;
    }

    private function afterSave(array $params = [])
    {
        return $params['saveID'] ? $this : null;
    }

    private function beforeSaveInsert(Entity $entity)
    {
        if(! isset($this->queryParams) || $this->queryParams->getQueryType()->name !== 'INSERT') {
            $this->query()->insert($entity)->go();
        }
        return $entity;
    }

    private function beforeSaveUpadate(Entity $entity) : Entity
    {
        if(! isset($this->queryParams) || $this->queryParams->getQueryType()->name !== 'UPDATE') {
            $this->query()->setDataFromEntities(true);
            $this->query()->update($entity)->go();
        }
        return $entity;
    }

    private function beforeSave(null|Entity|CollectionInterface $entity = null) : mixed
    {
        /** @var null|Entity|CollectionInterface */
        $en = is_null($entity) ? $this->entity : $entity;
        if ($en instanceof CollectionInterface) {
            return $en->count() > 0 ? $en->all()[0] : null;
        }
        return $en;
    }
}