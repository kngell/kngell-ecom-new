<?php

declare(strict_types=1);

class SelectFieldsParameters extends FieldsParameters
{
    private FieldsParameters $FieldsParams;
    /**
     * Fields lefts after assigning $fields to entity.
     *
     * @var array
     */
    private array $fieldsSet;

    public function __construct(FieldsParameters $FieldsParams)
    {
        $this->FieldsParams = $FieldsParams;
    }

    public function get(Entity $entity): ?string
    {
        $fields = $this->entityCheck($entity);
        return implode(', ', $fields);
    }

    private function entityCheck(Entity $entity) : array
    {
        $fields = $this->FieldsParams->getFields();
        $prefix = $this->prefix($entity->table());
        if (! empty($fields)) {
            $fields = ArrayUtil::flatten_without_keys($fields);
            return $this->normalizeFields($fields, $prefix, $entity);
        }
        return [$prefix . '*'];
    }

    private function normalizeFields(array $selectors, string $prefix, Entity $entity) : array
    {
        $s = [];
        foreach ($selectors as $selector) {
            if (is_null($selector)) {
                continue;
            }
            if (is_string($selector)) {
                $parts = explode('|', $selector);
                if (count($parts) > 1) {
                    if (! str_starts_with(strtolower($parts[0]), 'count')) {
                        $s[] = $prefix . $parts[1];
                        ! $entity->exists($parts[1]) ? $this->fieldsSet[] = $parts[1] : '';
                    } else {
                        $s[] = $this->selectorCount($parts);
                    }
                } else {
                    $s[] = $prefix . strval($selector);
                    ! $entity->exists(strval($selector)) ? $this->fieldsSet[] = strval($selector) : '';
                }
            } else {
                $s[] = $selector;
                ! $entity->exists($selector) ? $this->fieldsSet[] = $selector : '';
            }
        }
        return $s;
    }

    private function selectorCount(array $parts) : string
    {
        $selectorResults = '';
        $prefix = isset($this->params['alias']) ? $this->params['alias'] . '.' : '';
        if (count($parts) == 3) {
            $selectorResults .= strtoupper($parts[0]) . '(' . $prefix . $parts[1] . ') ' . 'AS ' . $parts[2];
        } elseif (count($parts) == 2) {
            $selectorResults .= strtoupper($parts[0]) . '(' . $prefix . $parts[1] . ') ';
        } elseif (count($parts) == 1) {
            $selectorResults .= strtoupper($parts[0]) . '(*) ';
        } else {
            throw new BadQueryArgumentException('EImpossible to count rows');
        }
        return $selectorResults;
    }

    private function prefix(string $tbl) : string
    {
        // $prefix = $this->FieldsParams->getAlias();
        // if ($prefix == false) {
        return $this->FieldsParams->tableAlias($tbl) . '.';
        // }
        // return $prefix . '.';
    }
}