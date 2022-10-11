<?php

declare(strict_types=1);
class Model extends AbstractModel
{
    use ModelTrait;
    protected string $defaultMedia;

    private $_results;
    private int $_count;
    private bool $_softDelete = false;
    private bool $_deleted_item = false;
    private string $_current_ctrl_method = 'update';
    private int $_lasID;
    private bool $_flatDb;

    /**
     * Main Constructor
     * =======================================================================.
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $tableSchema, string $tableSchemaID, bool $flatDb = false)
    {
        $this->throwException($tableSchema, $tableSchemaID);
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->_flatDb = $flatDb;
        $this->properties();
        $this->_modelName = $this::class;
    }

    public function assign(array $data) : self
    {
        $this->entity->assign($data);
        return $this;
    }

    public function uploadFiles(string $dir, ?string $newName = null) : self
    {
        $mediakey = $this->getMediakey();
        if(is_null($mediakey)) {
            return $this;
        }
        $files = $this->request->getHttpFiles();
        if($files->count() <= 0 && isset($this->defaultMedia)) {
            $this->set($mediakey, serialize([$this->tableSchema . DS . $this->defaultMedia]));
            return $this;
        }
        foreach ($files->all() as $uploader) {
            $incommingPath = $this->tableSchema . DS . $uploader->getOriginalName();
            if (file_exists($dir . $incommingPath)) {
                if ($incommingPath == $this->entity->{$$mediakey}) {
                    return $this;
                } else {
                    $setter = $this->entity->getSetter($mediakey);
                    $this->entity->{$setter}(serialize([$incommingPath]));
                    return $this;
                }
            }
            $path[] = $uploader->saveFile($dir . $this->tableSchema, $newName);
        }
        $this->set($mediakey, serialize($paths));
        return $this;
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
        if ($id == null) {
            $en = $this->getEntity();
            if ($en->isInitialized($this->get_colID())) {
                $id = $en->{$en->getGetters($en->getColId())}();
            }
        }
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

    /**
     * Save Data insert or update
     * ============================================================.
     * @param array $params
     * @return ?object
     */
    public function save(?Entity $entity = null) : ?Object
    {
        $en = is_null($entity) ? $this->entity : $entity;
        if ($this->beforeSave($entity)) {
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
        }

        return null;
    }

    public function validator(array $items = []) : void
    {
        $this->validator->validate($items, $this);
    }

    /**
     * Throw an exception
     * ================================================================.
     * @return void
     */
    public function throwException(string $tableSchema, string $tableSchemaID): void
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            throw new BaseInvalidArgumentException('Your repository is missing the required constants. Please add the TABLESCHEMA and TABLESCHEMAID constants to your repository.');
        }
    }
}