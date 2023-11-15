<?php

declare(strict_types=1);
class MaxValidator extends CustomValidator
{
    public function runValidation()
    {
        $getter = $this->getModel()->getEntity()->getGetters($this->getField());
        $value = $this->getModel()->getEntity()->{$getter}();
        return (strlen($value) <= $this->getRule());
    }
}
