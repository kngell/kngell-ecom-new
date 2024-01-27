<?php

declare(strict_types=1);

class GroupAndSortParameters extends AbstractQueryStatement
{
    private array $params = [];

    public function __construct(array $params = [], ?string $method = null, ?CollectionInterface $children = null, ?QueryParamsHelper $helper = null)
    {
        $this->params = $params;
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        return match ($this->method) {
            'groupBy' => $this->groupBy(),
            'orderBy' => $this->orderBy(),
            default => ''
        };
    }

    private function orderBy() : array
    {
        $ob = [];
        foreach ($this->helper->normalize($this->params['params']) as $tbl => $params) {
            if (is_array($params)) {
                $parts = [];
                $parts = explode('|', $params[0]);
                if (count($parts) == 2) {
                    $ob[] = $parts[0] . '.' . $parts[1] . ' ' . $params[1];
                } else {
                    $ob[] = $params[0] . ' ' . $params[1];
                }
            }
        }
        return $ob;
    }

    private function groupBy() : array
    {
        $gb = [];
        $params = ArrayUtil::flatten_with_keys($this->params['params']);
        foreach ($params as $tbl => $params) {
            if (is_numeric($tbl)) {
                $parts = explode('|', $params);
                if (count($parts) == 2) {
                    $alias = $this->tblAlias($parts[0]);
                    $gb[] = (! $alias ? $parts[0] : $alias) . '.' . $parts[1];
                } else {
                    $gb[] = $parts[0];
                }
            } else {
                $gb[] = $tbl . '.' . $params;
            }
        }
        return $gb;
    }

    private function tblAlias(string $table) : string|bool
    {
        $tblAlias = $this->params['tblAlias'];
        foreach ($tblAlias as $tbl => $alias) {
            if ($table == $tbl) {
                return $alias;
            }
        }
        return false;
    }
}