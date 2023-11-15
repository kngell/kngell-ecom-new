<?php

declare(strict_types=1);
class NumericValidator extends CustomValidator
{
    public function runValidation()
    {
        $pass = true;
        $getter = $this->getModel()->getEntity()->getGetters($this->getField());
        $value = $this->getModel()->getEntity()->{$getter}();
        if (!empty($value)) {
            $pass = is_numeric($value);
        }
        return $pass;
    }
}
