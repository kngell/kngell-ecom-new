<?php

declare(strict_types=1);
class Model extends AbstractModel
{
    protected string $defaultMedia;

    /**
     * Main Constructor
     * =======================================================================.
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $tableSchema, string $tableSchemaID, bool $flatDb = false)
    {
        parent::__construct($tableSchema, $tableSchemaID, $flatDb);
    }

    public function assign(array|bool $data) : self
    {
        if($data) {
            $this->entity->assign($data);
        }
        return $this;
    }

    public function deleteFiles(array $files = []) : void
    {
    }

    public function filterUpload(array $new, string $mediakey) : array
    {
        $existingAry = [];
        if($this->isInitialized($mediakey)) {
            $getter = $this->entity->getGetters($mediakey);
            $existingAry = unserialize($this->entity->$getter());
        }
        if(count($new) > count($existingAry)) {
            foreach ($existingAry as $mediapth) {
                if(! in_array($mediapth, $new)) {
                    $new[] = $mediapth;
                }
            }
        }
        return [array_diff($existingAry, $new), $new];
    }

    public function guardedID(): array
    {
        return [];
    }

    /**
     * Get Detail
     * ===========================================================.
     * @param mixed $id
     * @param string $colID
     * @return self|null
     */
    public function getDetails(mixed $id = null, string $colID = '', string $mode = 'class') : ?self
    {
        if (null == $id) {
            throw new BaseException("Impossible de trouver l'enregistrement souhaité");
        }
        $data_query = $this->table()
            ->where([$colID != '' ? $colID : $this->get_colID() => $id])
            ->return($mode)
            ->build();

        return $this->findFirst($data_query);
    }

    public function all() : CollectionInterface
    {
        $this->table()->return('object');
        return new Collection($this->getAll()->get_results());
    }

    public function getAll() : ?self
    {
        return $this->find();
    }

    public function doRelease() : self
    {
        return $this->release();
    }

    public function getAllByIndex(mixed $id, string $return = '') : ?self
    {
        $this->table()->where([$this->getColIndex() => $id])->return($return == '' ? 'class' : $return);
        return $this->getAll();
    }

    public function getAllWithSearchAndPagin(array $args) : self
    {
        return $this->findWithSearchAndPagin($args);
    }

    public function getUniqueId(string $colid_name = '', string $prefix = '', string $suffix = '', int $token_length = 24) : mixed
    {
        $output = $prefix . $this->token->generate($token_length) . $suffix;
        while ($this->getDetails($output, $colid_name)->count() > 0) :
            $output = $prefix . $this->token->generate($token_length) . $suffix;
        endwhile;

        return $output;
    }

    public function customQuery(string $query = '', array $conditions = [])
    {
        $result = $this->repository->customQuery($query);
    }

    public function beginTransaction() : bool
    {
        return $this->repository->beginTransaction();
    }

    public function exec(string $query) : int|false
    {
        return $this->repository->exec($query);
    }

    public function inTransaction() : bool
    {
        return $this->repository->inTransaction();
    }

    public function rollBack() : bool
    {
        return $this->repository->rollBack();
    }

    /**
     * Save Data insert or update
     * ============================================================.
     * @param array $params
     * @return ?self
     */
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

    public function existsLastId() : bool
    {
        return isset($this->_lasID);
    }

    public function validator(array $items = []) : void
    {
        $this->validator->validate($items, $this);
    }
}