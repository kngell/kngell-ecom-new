<?php

declare(strict_types=1);
class ModelHelper
{
    public function getRepoArgs(array $data = [], array $tables = []) : array
    {
        $results = [];
        if (! empty($data)) {
            foreach ($data as $key => $value) {
                if (! is_array($value)) {
                    throw new BaseInvalidArgumentException($value . ' must be an array');
                }
                if (in_array($key, array_keys($data))) {
                    if ($key == 'table_join') {
                        $results[$key] = array_merge($value, $tables);
                    } else {
                        $results[$key] = $value;
                    }
                }
            }
        }

        return [$results['selectors'] ?? [], $results['conditions'] ?? [], $results['parameters'] ?? [], $results['options'] ?? ''];
    }

    /**
     * Transform Key -> transform source key from old to new key when present on $item
     * ==================================================================================.
     * @param array $source
     * @param array $item
     * @return array
     */
    public function transform_keys(array $source = [], array | null $newKeys = []) : array
    {
        $S = $source;
        if (isset($newKeys) && ! empty($newKeys)) {
            foreach ($source as $key => $val) {
                if (isset($newKeys[$key]) && $newKeys[$key] !== $key) {
                    $S = $this->_rename_arr_key($key, $newKeys[$key], $S);
                }
            }
        }
        return $S;
    }

    /**
     * Rename keys
     * ==================================================================================.
     * @param string $oldkey
     * @param string $newkey
     * @param array $arr
     * @return array|bool
     */
    private function _rename_arr_key(string $oldkey, string $newkey, array $arr = []) : bool|array
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