<?php

declare(strict_types=1);
class QueryParamsHelper
{
    public function normalize(array $conditions = [], ?string $method = null) : array
    {
        if (null !== $method && ! str_ends_with($method, 'In')) {
            // if (! in_array($method, ['set'])) {
            $conditions = $this->parseCondition($conditions);
            return $this->assocToSequencials($conditions);
            // }
        }
        return $conditions;
    }

    public function assocToSequencials(array $conditions) : array
    {
        $newArr = [];
        $ncond = [];
        foreach ($conditions as $key => $condition) {
            if (is_array($condition) && ArrayUtil::isAssoc($condition)) {
                foreach ($condition as $key => $value) {
                    $ncond[] = $key;
                    $ncond[] = $value;
                    $newArr[] = $ncond;
                    $ncond = [];
                }
            } elseif (is_array($condition) && ! ArrayUtil::isAssoc($condition)) {
                $newArr[] = $condition;
            } else {
                $newArr[$key] = $condition;
            }
        }
        return ! empty($newArr) ? $newArr : $conditions;
    }

    public function parseCondition(array $conditions) : array
    {
        $newArray = [];
        $narr = [];
        if (is_array($conditions) && count($conditions) == 1 && ArrayUtil::isAssoc($conditions)) {
            $newArray[0] = key($conditions);
            $newArray[1] = $conditions[key($conditions)];
            return $newArray;
        }
        foreach ($conditions as $key => $condition) {
            $next = next($conditions);
            if (! is_array($condition)) {
                $narr[] = is_string($condition) ? strval($condition) : $condition;
                if ($next != false && is_array($next)) {
                    $newArray[] = $narr;
                    $narr = [];
                }
            } else {
                $newArray[] = $condition;
            }
        }
        if (count($narr) == count($conditions)) {
            $newArray = $narr;
            $narr = [];
        }
        return ! empty($newArray) ? $newArray : $conditions;
    }
    // public function standardizeConditions(array $condition) : array
    // {
    //     if (is_array($condition)) {
    //         while (count($condition) == count($condition, COUNT_RECURSIVE)) {//not is multidimentional
    //             $condition = $this->normalize($condition);
    //         }
    //     }
    //     return $condition;
    // }

    public function leveledUp(array $array) : array
    {
        $deep = ArrayUtil::deepthLevel($array);
        if ($deep > 1) {
            if (! ArrayUtil::isMixed($array)) {
                $newArray = call_user_func_array('array_merge', $array);
            }
            $newArray = $this->normalize($newArray ?? $array);
        } else {
            $newArray = $array;
        }
        // $arr = [];

        // // if ($deep > 1) {
        // foreach ($array as $item) {
        //     $deep = ArrayUtil::deepthLevel($item);
        //     if (is_array($item)) {
        //         $arr = array_merge($arr, $this->leveledUp($item));
        //     } else {
        //         $arr[] = $item;
        //     }
        // }
        return $newArray;

        // if (is_array($array) && count($array) != count($array, COUNT_RECURSIVE)) {
        //     $array = $this->normalize($array);
        // }

        //  else {
        //     $arr = $array;
        // }
        // return $arr;
    }

    public function prepareCondition(array $conditions) : array
    {
        $whereCondition = [];
        if (ArrayUtil::isAssoc($conditions)) {
            foreach ($conditions as $key => $condition) {
                $whereCondition[0] = $key;
                $whereCondition[1] = $condition;
            }
            return $whereCondition;
        } else {
            return [$conditions];
        }
    }
}