<?php

declare(strict_types=1);

class GlobalVariables implements GlobalVariablesInterface
{
    /**
     * Get $_GET
     * =================================================================================.
     * @param string $key
     * @return mixed
     */
    public function getGet(?string $key = null) : mixed
    {
        $global = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        if ($global == null) {
            return '';
        }
        if (null != $key) {
            return $global[$key] ?? null;
        }
        return array_map('strip_tags', $global ?? []);
    }

    /**
     * Get $_POST
     * =================================================================================.
     * @param string $key
     * @return mixed
     */
    public function getPost(?string $key = null) : mixed
    {
        $global = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? null;
        if (null != $key) {
            return $global[$key] ?? null;
        }

        return $global ?? [];
    }

    /**
     * Get $_Cookies
     * =================================================================================.
     * @param string $key
     * @return mixed
     */
    public function getCookie(?string $key = null) : mixed
    {
        $global = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        if (null != $key) {
            return $global[$key] ?? null;
        }

        return array_map('strip_tags', $global ?? []);
    }

    /**
     * Get $_Cookies
     * =================================================================================.
     * @param string $key
     * @return mixed
     */
    public function getServer(?string $key = null) : mixed
    {
        if (isset($_SERVER)) {
            $global = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
            if (null != $key) {
                if (!isset($global[strtoupper($key)])) {
                    if (!isset($_SERVER[strtoupper($key)])) {
                        return '';
                    }

                    return $_SERVER[strtoupper($key)];
                }

                return $_SERVER[strtoupper($key)] ?? '';
            }
        }

        return array_map('strip_tags', $_SERVER ?? []);
    }

    /**
     * Get $_FILES
     * =====================================================================================.
     * @return array
     */
    public function getFiles() : array
    {
        return filter_var_array($_FILES, FILTER_DEFAULT) ?? null;
    }
}
