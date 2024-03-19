<?php

declare(strict_types=1);

class FieldsParameters extends AbstractStatementParameters
{
    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($params, $method, $baseMethod, $queryType);
        isset($this->params['tbl']) ? $this->tbl = $this->params['tbl'] : '';
        isset($this->params['fields']) ? $this->fields = $this->params['fields'] : '';
        isset($this->params['entity']) ? $this->entity = $this->params['entity'] : '';
    }

    public function proceed(): array
    {
        if (isset($this->entity)) {
            /** @var Entity */
            $entity = $this->entity instanceof CollectionInterface ? $this->entity->first() : $this->entity;
            $factory = new FieldsParametersFactory($this);
            $parameters = $factory->create($this->queryType, $this->method);
            $this->query = $parameters->get($entity);
        }
        return [$this->query, $this->parameters, $this->bind_arr];
    }

    public function get(Entity $entity): ?string
    {
        return '';
    }

    private function selectors(array $selectors) : array
    {
        if (isset($this->tbl) && isset($this->alias)) {
            $this->params['tbl'] = $this->tbl;
            $this->params['alias'] = $this->alias;
        }
        if (empty($selectors) && $this->method == 'select') {
            $prefix = isset($this->params['alias']) ? $this->params['alias'] . '.' : '';
            return [$prefix . '* '];
        }
        return $this->normalizeSelectors($selectors);
    }

    private function normalizeSelectors(array $selectors) : array
    {
        $s = [];
        if ($this->queryType === 'SELECT') {
            $prefix = isset($this->params['alias']) ? $this->params['alias'] . '.' : '';
        } else {
            $prefix = '';
        }

        foreach ($selectors as $selector) {
            if (is_null($selector)) {
                continue;
            }
            if (is_string($selector)) {
                $parts = explode('|', $selector);
                if (count($parts) > 1) {
                    if (! str_starts_with(strtolower($parts[0]), 'count')) {
                        $s[] = $prefix . $parts[1];
                    } else {
                        $s[] = $this->selectorCount($parts);
                    }
                } else {
                    $s[] = $prefix . strval($selector);
                }
            } else {
                $s[] = $selector;
            }
        }
        return $this->helper->leveledUp($s);
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
}