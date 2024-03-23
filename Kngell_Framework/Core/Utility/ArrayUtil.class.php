<?php

declare(strict_types=1);

final class ArrayUtil
{
    private function __construct()
    {
    }

    public static function transform_keys(array $source = [], array | null $newKeys = []) : array
    {
        $S = $source;
        if (isset($newKeys) && ! empty($newKeys)) {
            foreach ($source as $key => $val) {
                if (isset($newKeys[$key]) && $newKeys[$key] !== $key) {
                    $S = self::_rename_arr_key($key, $newKeys[$key], $S);
                }
            }
        }
        return $S;
    }

    public static function isAssoc(array $array) : bool
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    public static function IsEqualArray(array $array1, array $array2) : bool
    {
        return array_diff($array1, $array2) == [] && array_diff($array2, $array1) == [];
    }

    public static function uniqueObjectByKey(array $array, string $key) : array
    {
        $result = [];
        $duplicates = [];
        foreach ($array as $i) {
            if (! isset($result[$i->{$key}])) {
                $result[$i->{$key}] = $i;
            } else {
                $duplicates[] = $i->vId;
            }
        }
        sort($result); //Add this if you want to clean up the keys.
        return [$result, $duplicates];
    }

    /**
     * Create multiple arrays from one associative array.
     *
     * @param array $array
     * @return array
     */
    public static function multiplesArraysFromAssoc(array $array) : array
    {
        if (self::isAssoc($array)) {
            $newArr = [];
            foreach ($array as $key => $value) {
                $newArr[] = [$key => $value];
            }
            return $newArr;
        }
        throw new Exception('Array is not associative');
    }

    public static function firstElement(array $array) : mixed
    {
        return empty($array) ? null : $array[0];
    }

    public static function deepthLevel(array $arr)
    {
        if (! is_array($arr)) {
            return 0;
        }
        $arr = json_encode($arr);
        $varsum = 0;
        $depth = 0;
        for ($i = 0; $i < strlen($arr); $i++) {
            $varsum += intval($arr[$i] == '[') - intval($arr[$i] == ']');
            if ($varsum > $depth) {
                $depth = $varsum;
            }
        }
        return $depth;
    }

    public static function is_indexed_array(array &$arr) : bool
    {
        for (reset($arr); is_int(key($arr)); next($arr));
        return is_null(key($arr));
    }

    public static function is_sequential_array(array &$arr, int $base = 0) : bool
    {
        for (reset($arr), $base = (int) $base; key($arr) === $base++; next($arr));
        return is_null(key($arr));
    }

    public static function normalise($array) : array
    {
        $result = [];
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $k => $v) {
            $result[] = [$k => $v];
        }
        return array_merge(...$result);
    }

    public static function deepestsubArray(array $array) : array
    {
        $it = new \RecursiveArrayIterator($array);
        $it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        $max_depth = 0;
        $items = $deepmost = [];
        foreach ($it as $item) {
            $depth = $it->getDepth();        // current subiterator depth
            if ($depth > $max_depth) {       // determining max depth
                $max_depth = $depth;
                $items = [];
            }
            if (is_array($item)) {
                $items[$depth][] = $item;
            }
        }
        if ($items) {
            $max_key = max(array_keys($items));  // get max key pointing to the max depth
            $deepmost = $items[$max_key];
            unset($items);
        }
        return $deepmost;
    }

    public static function searchArray(string $needle, array $haystack) : array
    {
        $matched = [];
        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $key => $value) {
                if ((string) $key === (string) $needle) {
                    if (is_array($value)) {
                        $matched = $value;
                    } else {
                        $matched[] = $value;
                    }
                } else {
                    if (is_array($value) && count($value) > 0) {
                        self::searchArray($needle, $value, $matched);
                    }
                }
            }
        }
        return $matched;
    }

    public static function isMixed(array $array) : bool
    {
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                return true;
                break;
            }
        }
        return false;
    }

    public static function filter_recursive(array $array) : array
    {
        if (! is_array($array)) {
            return $array;
        }
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $array[$key] = self::filter_recursive($item);
            }
        }
        return array_filter($array);
    }

    public static function flatten(array $array) : array
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, self::flatten($value));
            } else {
                $results[$key] = $value;
            }
        }

        return $results;
    }

    /** get Values from an associative array */
    public static function valuesFromArray($array) : array
    {
        $values = [];
        foreach ($array as $key => $value) {
            $values[] = $value;
        }
        return $values;
    }

    public static function cleanUp(array $array) : array
    {
        return self::flatten(self::filter_recursive($array));
    }

    public static function flatten_with_keys(array $array): array
    {
        $recursiveArrayIterator = new RecursiveArrayIterator(
            $array,
            RecursiveArrayIterator::CHILD_ARRAYS_ONLY
        );
        $iterator = new RecursiveIteratorIterator($recursiveArrayIterator);
        return iterator_to_array($iterator);
    }

    public static function flatten_without_keys(array $array): array
    {
        $recursiveArrayIterator = new RecursiveArrayIterator(
            $array,
            RecursiveArrayIterator::CHILD_ARRAYS_ONLY
        );
        $iterator = new RecursiveIteratorIterator($recursiveArrayIterator);
        return iterator_to_array($iterator, false);
    }

    public static function flattenArray(?array $array = null): array
    {
        if (is_array($array)) {
            $arraySingle = [];
            foreach ($array as $arr) {
                foreach ($arr as $val) {
                    $arraySingle[] = $val;
                }
            }
            return $arraySingle;
        }
    }

    public static function flattenArrayRecursive(?array $array = null): array
    {
        $flatArray = [];
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $value) {
            $flatArray[] = $value;
        }
        return $flatArray;
    }

    public static function isMultidimentional(array $array) : bool
    {
        if (count($array) == count($array, COUNT_RECURSIVE)) {
            return false;
        }
        return true;
    }

    private static function _rename_arr_key(string $oldkey, string $newkey, array $arr = []) : bool|array
    {
        if (array_key_exists($oldkey, $arr)) {
            $arr[$newkey] = $arr[$oldkey];
            unset($arr[$oldkey]);

            return $arr;
        } else {
            return false;
        }
    }
}