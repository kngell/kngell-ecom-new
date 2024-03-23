<?php

declare(strict_types=1);

class GroupAndSortParameters extends AbstractStatementParameters
{
    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($params, $method, $baseMethod, $queryType);
        $this->tbl = $this->params['tbl'];
        isset($this->params['alias']) ? $this->alias = $this->params['alias'] : '';
    }

    public function proceed(): array
    {
        list($r, $this->parameters, $this->bind_arr) = match ($this->method) {
            'groupBy' => [$this->groupBy(), [], []],
            'orderBy' => [$this->orderBy(), [], []],
            default => ['', [], []]
        };
        $this->query = implode(', ', $r);
        return [$this->query, $this->parameters, $this->bind_arr];
    }

    private function orderBy() : array
    {
        $ob = [];
        $params = $this->helper->normalize($this->params['data'], $this->method);
        foreach ($params as $tbl => $param) {
            if (is_array($param)) {
                $parts = [];
                $parts = explode('|', $param[0]);
                if (count($parts) == 2) {
                    $ob[] = $parts[0] . '.' . $parts[1] . ' ' . $param[1];
                } else {
                    $ob[] = $param[0] . ' ' . $param[1];
                }
            }
        }
        return $ob;
    }

    private function groupBy() : array
    {
        $gb = [];
        $params = $this->params['data'];
        $params = $this->normalizeCounters($params);

        foreach ($params as $tbl => $param) {
            $alias = is_string($tbl) ? $this->tableAlias($tbl) : '';
            if (is_numeric($tbl)) {
                $gb[] = $param;
            } else {
                $gb[] = $alias . '.' . $param;
            }
        }
        return $gb;
    }

    private function normalizeCounters(array $params) : array
    {
        $newParams = [];
        foreach ($params as $key => $param) {
            if (is_array($param) && count($param) == 1) {
                $newParams[key($param)] = $param[key($param)];
            } elseif (is_string($param)) {
                $parts = explode('|', $param);
                if (count($parts) == 2) {
                    $newParams[$parts[0]] = $parts[1];
                } elseif (count($parts) == 1) {
                    $newParams[] = $param;
                } else {
                    throw new BadQueryArgumentException("Bad parameters for {$this->method}");
                }
            } else {
                throw new BadQueryArgumentException("Bad parameters for {$this->method}");
            }
        }
        return $newParams;
    }

    private function tblAlias(string $table) : string|bool
    {
        $tblAlias = $this->params['tbl_alias'];
        foreach ($tblAlias as $tbl => $alias) {
            if ($table == $tbl) {
                return $alias;
            }
        }
        return false;
    }
}