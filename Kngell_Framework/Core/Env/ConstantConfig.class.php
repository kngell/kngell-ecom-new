<?php

declare(strict_types=1);
class ConstantConfig
{
    /**
     * DSEPERATOR ALIASES
     * -----------------------------------------------------------------------.
     * @return self
     */
    public function ds() : self
    {
        // defined('URL_SEPARATOR') or define('URL_SEPARATOR', '/');
        // defined('PS') or define('PS', PATH_SEPARATOR);
        // defined('US') or define('US', URL_SEPARATOR);
        // defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        // return $this;
    }

    /**
     * Application Constant.
     * -----------------------------------------------------------------------.
     * @param string $appRoot
     * @return self
     */
    public function appConstants() : self
    {
        return $this;
    }
}