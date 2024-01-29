<?php

declare(strict_types=1);

abstract class AbstractModel
{
    use ModelGetterAndSetterTrait;
    use ModelTrait;
    protected string $_modelName;
    protected QueryParamsInterface $queryParams;
    protected RepositoryInterface|FileStorageRepositoryInterface $repository;
    protected MoneyManager $money;
    protected Entity $entity;
    protected ModelHelper $helper;
    // protected SessionInterface $session;
    protected CookieInterface $cookie;
    // protected CacheInterface $cache;
    protected Token $token;
    // protected RequestHandler $request;
    // protected ResponseHandler $response;
    protected Validator $validator;
    protected bool $validates = true;
    protected array $validationErr = [];
    protected string $tableSchema;
    protected string $tableSchemaID;
    protected mixed $_results;
    protected string $_colID;
    protected string $_table;
    protected int $_count;
    protected bool $_flatDb;
    protected int $_lasID;
    protected string $_colIndex = '';
    protected ?PDOStatement $_statement;
    protected DatabaseConnexionInterface $_con;

    public function __construct(string $tableSchema, string $tableSchemaID, bool $flatDb = false)
    {
        $this->throwException($tableSchema, $tableSchemaID);
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->_flatDb = $flatDb;
        $this->properties();
        $this->_modelName = $this::class;
    }

    /*
     * Prevent Deleting Ids
     * ------------------------------------------------------------.
     * @return array
     */
    abstract public function guardedID() : array;

    public function table(?string $tbl = null, ...$fields) : QueryParamsInterface
    {
        return $this->queryParams->setBaseOptions(
            $tbl ?? $this->_table,
            $this->entity
        )->query($tbl, $fields);
    }

    public function query(?string $queryType = null, ?string $tbl = null, ...$params) : QueryParamsInterface
    {
        return $this->queryParams->setBaseOptions(
            $tbl ?? $this->_table,
            $this->entity
        )->query($queryType, $params);
    }

    public function tableRecursive(?string $tbl = null, mixed $columns = null) : QueryParamsInterface
    {
        return $this->table($tbl, $columns, true);
    }

    public function conditions() : self
    {
        // if ($this->queryParams->hasConditions()) {
        //     return $this;
        // }
        $colID = $this->entity->getColId();
        if (! $this->entity->isInitialized($colID)) {
            throw new BaseException('unable to update row!');
        }
        $this->query()->where([$colID => $this->entity->{$this->entity->getGetters($colID)}()])->build();
        // $this->table()->where([$colID => $this->entity->{$this->entity->getGetters($colID)}()])->build();
        return $this;
    }

    /*
     * Global Before Save
     * ================================================================.
     * @return void
     */
    public function beforeSave(null|Entity|CollectionInterface $entity = null) : mixed
    {
        /** @var null|Entity|CollectionInterface */
        $en = is_null($entity) ? $this->entity : $entity;
        if ($en instanceof CollectionInterface) {
            return $en->count() > 0 ? $en->all()[0] : null;
        }
        return $en;
    }

    public function beforeSaveUpadate(Entity $entity) : Entity
    {
        return $entity; //fields;
        // $current = new DateTime();
        // $key = current(array_filter(array_keys($fields), function ($field) {
        //     return str_starts_with($field, 'update');
        // }));
        // if ($key && !is_array($key)) {
        //     $f[$key] = $current->format('Y-m-d H:i:s');
        // }
        // if (isset($f[$this->get_colID()])) {
        //     unset($f[$this->get_colID()]);
        // }
    }

    public function beforeSaveInsert(Entity $entity)
    {
        return $entity;
    }

    public function afterSave(array $params = [])
    {
        return $params['saveID'] ? $this : null;
    }

    //Before delete
    public function beforeDelete(Entity $entity)
    {
        return ! is_null($entity) ? true : false;
    }

    //After delete
    public function afterDelete($params = [])
    {
        $params = null;

        return true;
    }

    /*
     * Get Col ID or TablschemaID.
     *
     * @return string
     */
    public function get_colID() : string
    {
        return isset($this->_colID) ? $this->_colID : '';
    }

    public function runValidation(CustomValidator $validator) : void
    {
        $status = $validator->run();
        if (! $status) {
            $this->validates = false;
            $this->validationErr[$validator->getField()] = $validator->getMsg();
        }
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

    public function getModelName() : string
    {
        return $this->_modelName;
    }

    public function getTableColumn() : array
    {
        $columns = $this->repository->get_tableColumn(['return_mode' => 'object']);
        if ($columns->count() > 0) {
            $columnsName = [];
            foreach ($columns->get_results() as $column) {
                $columnsName[] = '$' . $column->Field;
            }
        }
        return $columnsName;
    }

    public function defaultShippingClass() : ?int
    {
        $en = $this->getEntity();
        if (! $this->isInitialized('shipping_class')) {
            return $this->container(ShippingClassManager::class)->getDefault();
            // if ($defaultShClass->count() === 1) {
            //     return current($defaultShClass->get_results());
            // }
        }
        $getter = $en->getGetters('shipping_class');
        return $en->$getter();
    }
}