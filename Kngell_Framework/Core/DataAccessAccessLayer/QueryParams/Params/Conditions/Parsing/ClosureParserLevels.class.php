<?php

declare(strict_types=1);

class ClosureParserLevels extends AbstractClosureParser
{
    public function get(array $conditions = []): array
    {
        $this->conditions = [];

        $cc = [];
        $subCond = false;
        if ($closure = $this->helper->isClosure($conditions)) {
            $closure->__invoke($this);
            $subCond = true;
        } else {
            $this->conditions = $conditions;
        }
        if ($subCond) {
            $cc['subCond'] = $this->conditions;
        }
        return ! $subCond ? $this->conditions : $cc;
    }

    public function addParser(AbstractClosureParser $parser) : bool
    {
        $parser->level = $this->level + 1;
        $this->collectionParser->add($parser);
        return true;
    }
}