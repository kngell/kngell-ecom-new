<?php

declare(strict_types=1);

class SelectParameters extends AbstractQueryStatement
{
    private array $params = [];

    public function __construct(array $params = [], ?string $method = null, ?CollectionInterface $children = null, ?QueryParamsHelper $helper = null)
    {
        $this->params = $params;
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        $selectors = isset($this->params['selectors']) ? $this->params['selectors'] : [];
        return $this->selectors($selectors);
    }

    private function selectors(array $selectors) : array
    {
        $selectors = ArrayUtil::normalise($selectors);
        if (empty($selectors)) {
            $prefix = isset($this->params['alias']) ? $this->params['alias'] . '.' : '';
            return [$prefix . '* '];
        }
        if (count($selectors) != count($selectors, COUNT_RECURSIVE)) {
            $selectors = array_merge(...$this->helper->normalize($selectors));
        }
        return $this->normalizeSelectors($selectors);
    }

    private function normalizeSelectors(array $selectors) : array
    {
        $s = [];
        $prefix = isset($this->params['alias']) ? $this->params['alias'] . '.' : '';
        foreach ($selectors as $selector) {
            $parts = explode('|', $selector);
            if (count($parts) > 1) {
                if (str_starts_with(strtolower($parts[0]), 'count')) {
                    $s[] = $this->selectorCount($parts);
                } else {
                    $s[] = $prefix . $parts[1];
                }
            } else {
                $s[] = $prefix . $selector;
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