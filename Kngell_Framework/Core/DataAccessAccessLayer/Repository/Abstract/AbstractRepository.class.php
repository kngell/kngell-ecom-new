<?php

declare(strict_types=1);

abstract class AbstractRepository
{
    protected Entity|CollectionInterface $entity;
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function fields(array $conditions = []) : array
    {
        if ($this->entity instanceof CollectionInterface) {
            return $this->collectionFields($conditions);
        }
        $fields = $this->entity->getInitializedAttributes();
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if (array_key_exists($key, $fields)) {
                    unset($fields[$key]);
                }
            }
        }
        return $fields;
    }

    protected function collectionFields(array $conditions) : array
    {
        $en = $this->entity->all()[0];
        $fields = array_keys($en->getInitializedAttributes());
        $values = [];
        /** @var Entity[] */
        $ens = $this->entity;
        foreach ($ens as $entity) {
            $values[] = array_values($entity->getInitializedAttributes());
        }
        return ['fields' => $fields, 'values' => $values];
    }

    protected function isArray(array $conditions) : bool
    {
        if (!is_array($conditions)) {
            throw new RepositoryInvalidArgumentException('Argument Supplied is not an array');
        }

        return true;
    }

    protected function isEmpty(int $id) : bool
    {
        if (empty($id)) {
            throw new RepositoryInvalidArgumentException('Argument shuold not be empty');
        }

        return true;
    }
}