<?php

declare(strict_types=1);
class UniqueValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        $value = $this->userData[$this->field];
        if (! empty($value)) {
            $modelString = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->rule))) . 'Manager';
            $user = $this->factory->create($modelString);
            $user->getDetails($this->field, $value);
            if ($user->count() > 0) {
                return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
            }
            return true;
        }
        return 'noPassed';
    }

    // public function runValidation()
    // {
    //     $field = (is_array($this->getField())) ? $this->getField()[0] : $this->getField();
    //     $en = $this->getModel()->getEntity();
    //     $getter = $en->getGetters($this->getField());
    //     $value = $en->{$getter}();
    //     $this->getModel()->table()->where([$field => $value])->return('class');
    //     $other = $this->getModel()->getAll();
    //     $table_id = $this->getModel()->getTableSchemaID() ?? null;
    //     if ($other->count() <= 0) {
    //         return true;
    //     }
    //     if (( new ReflectionProperty($en, $en->getColId('id', true)))->isInitialized($en)) {
    //         foreach ($other->get_results() as $item) {
    //             if (isset($table_id) && $item->$table_id == $en->{$en->getGetters($en->getColId())}()) {
    //                 return true;
    //             }
    //         }
    //     }

    //     return ! $other->count() >= 1;
    // }
}