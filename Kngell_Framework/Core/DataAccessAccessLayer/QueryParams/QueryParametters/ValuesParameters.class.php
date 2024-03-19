<?php

declare(strict_types=1);
class ValuesParameters extends AbstractStatementParameters
{
    private EntityValuesFactory $entityValuesFactory;

    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($params, $method, $baseMethod, $queryType);
        isset($this->params['tbl']) ? $this->tbl = $this->params['tbl'] : '';
        isset($this->params['alias']) ? $this->alias = $this->params['alias'] : '';
        isset($this->params['values']) ? $this->values = $this->params['values'] : '';
        isset($this->params['cte']) ? $this->cte = $this->params['cte'] : '';
        isset($this->params['cteAlias']) ? $this->cteAlias = $this->params['cteAlias'] : '';
        $this->entityValuesFactory = new EntityValuesFactory($this);
    }

    public function proceed(): array
    {
        if (isset($this->entity)) {
            $values = '';
            $entityValues = $this->entityValuesFactory->create($this->queryType);
            if ($this->entity instanceof Entity) {
                $values = $entityValues->getValues($this->entity);
            } elseif ($this->entity instanceof Collectioninterface) {
                $entities = $this->entities();
                foreach ($entities as $key => $entity) {
                    $sep = $key == array_key_last($entities) ? '' : ',';
                    $values .= $entityValues->getValues($entity, $key) . $sep;
                }
            } else {
                throw new BadQueryArgumentException('no data to insert');
            }
            $this->query = $values;
        }
        return [$this->query, $this->parameters, $this->bind_arr];
    }

    public function getValues(Entity $entity, ?int $enKey = null): ?string
    {
        return '';
    }

    public function update() : string
    {
        $colId = $this->colId();
        $this->alias = $this->getTblAlias($this->tbl);
        $this->cteAlias = $this->tableAlias($this->cte);
        $update = $this->queryType . ' ' . $this->tbl . ' AS ' . $this->alias;
        $update .= ' INNER JOIN ' . $this->cte . ' AS ' . $this->cteAlias;
        $update .= ' ON ' . $this->cteAlias . '.' . $colId . ' = ' . $this->alias . '.' . $colId;
        $fields = array_keys($this->entity->first()->getInitializedAttributes());
        $setsValues = '';
        foreach ($fields as $key => $field) {
            $sep = $key == array_key_last($fields) ? '' : ', ';
            if ($field !== $colId) {
                $setsValues .= $this->alias . '.' . $field . ' = ' . $this->cteAlias . '.' . $field . $sep;
            }
        }
        $update .= ' SET ' . $setsValues;
        return $update;
    }

    private function entities() : array
    {
        $entities = $this->entity->all();
        if ($this->queryType == 'UPDATECTE') {
            $i = 0;
            while (count($entities) > 1) {
                unset($entities[$i]);
                $i++;
            }
        }
        return array_values($entities);
    }

    private function colId() : string
    {
        if (isset($this->entity)) {
            if ($this->entity instanceof Entity) {
                $colId = $this->entity->getColId();
            } elseif ($this->entity instanceof CollectionInterface) {
                $colId = $this->entity->first()->getColId();
            } else {
                $colId = '';
            }
        }
        return $colId;
    }

    private function entityValues() : string
    {
        $this->entityChecking($this->entity);
        $values = $this->entity->getInitializedAttributes();
        if (empty($values)) {
            throw new BadQueryArgumentException("There's no data.");
        }
        if ($this->queryType == 'INSERT' && $this->entity->disablePrimaryKey()) {
            $this->values = $values;
            return $this->arrayPrefixer($values);
        }
        if ($this->queryType == 'UPDATE') {
            $query = '';
            foreach ($values as $field => $value) {
                $sep = $value == end($values) ? '' : ', ';
                $this->parameters[$field] = $value;
                $query .= $field . ' = :' . $field . $sep;
            }
            return $query;
        }
    }

    private function entitiesCollectionValues() : string
    {
        $query = '';
        $entities = $this->entity->all();
        $this->useBrace();
        if ($this->queryType == 'INSERT') {
            foreach ($entities as $key => $entity) {
                $this->entityChecking($entity);
                if ($entity->disablePrimaryKey()) {
                    $values = $entity->getInitializedAttributes();
                    if (empty($values)) {
                        throw new BadQueryArgumentException("There's no data to insert in the database");
                    }
                    $sep = $key == array_key_last($entities) ? '' : ', ';
                    $query .= $this->braceOpen . $this->arrayPrefixer($values, $key) . $this->braceClose . $sep;
                    $this->values[] = $values;
                }
            }
            return $query;
        }
        if ($this->queryType == 'UPDATE') {
            $cte = '';
            $rows = '';
            foreach ($entities as $key => $entity) {
                $this->entityChecking($entity);
                $values = $entity->getInitializedAttributes();
                $row = '';
                $colId = $entity->getColId();
                $fields = [];
                $rowSep = $key == array_key_last($entities) ? '' : ', ';
                foreach ($values as $field => $value) {
                    $sep = $value == end($values) ? '' : ', ';
                    $this->parameters[$field . $key] = $value;
                    $v = ':' . $field . $key . $sep;
                    $row .= $v;
                }
                $rows .= '(' . $row . ')' . $rowSep;
            }
            return 'VALUES ' . $rows . $this->update($colId);
        }
    }

    private function entityChecking(Entity $entity) : void
    {
        if (! $entity instanceof Entity) {
            throw new BadQueryArgumentException('You must provide an Entity object or a collection of entities');
        }
        if (! $entity->isPropertiesSet()) {
            throw new BadQueryArgumentException("There's no data to insert in the database");
        }
    }
}