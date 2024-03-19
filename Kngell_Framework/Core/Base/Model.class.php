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

    public function getDetails(mixed $id = null, string $colID = '', string $mode = 'class') : ?self
    {
        if (null == $id) {
            throw new BaseException("Impossible de trouver l'enregistrement souhaité");
        }
        $data_query = $this->query()
            ->where([$colID != '' ? $colID : $this->tableSchemaID => $id])
            ->return($mode);

        return $this->find();
    }

    public function save(null|Entity|CollectionInterface $entity = null): ?self
    {
        $en = $this->beforeSave($entity);
        if ($en->isInitialized($en->getColId())) {
            $en = $this->beforeSaveUpadate($en);
            $save = $this->update();
        } else {
            $en = $this->beforeSaveInsert($en);
            $save = $this->insert();
        }
        if ($save->count() > 0) {
            $params['saveID'] = $save ?? '';
            return $this->afterSave($params);
        }
        return null;
    }

    public function insert() : self
    {
        /** @var DataMapperInterface */
        $result = $this->repository()->create();
        $this->setAllReturnedValues($result);
        return $this;
    }

    public function update() : self
    {
        $result = $this->repository()->update();
        $this->setAllReturnedValues($result);
        return $this;
    }

    public function deleteWithConditions(null|Entity $entity) : self
    {
        null === $entity ? $entity = $this->entity : '';
        $entity = $this->beforeDelete($entity);
        $result = $this->repository()->delete();
        $this->setAllReturnedValues($result);
        return $this;
    }

    public function delete(null|Entity $entity = null) : ?self
    {
        return $this->deleteWithConditions($entity);
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

    private function beforeDelete(Entity $entity) : Entity
    {
        if(! isset($this->queryParams) || $this->queryParams->getQueryType()->name !== 'DELETE') {
            $colID = $entity->getColId();
            $this->query()->delete($entity)->where([$colID => $this->entity->getFieldValue($colID)])->go();
        }
        return ! is_null($entity) ? $entity : false;
    }

    //After delete
    private function afterDelete($params = [])
    {
        $params = null;
        return true;
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