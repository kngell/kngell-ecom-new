<?php

declare(strict_types=1);

class GlobalVariables implements GlobalVariablesInterface
{
    private readonly array $getVar;
    private readonly array $postVar;
    private readonly array $cookiesVar;
    private readonly array $filesVar;
    private readonly array $serverVar;

    public function __construct()
    {
        $this->getVar = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $this->postVar = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];
        $this->cookiesVar = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
        $this->serverVar = $_SERVER; //filter_input_array(INPUT_SERVER, FILTER_DEFAULT) ?? [];
        $this->filesVar = filter_var_array($_FILES, FILTER_DEFAULT) ?? [];
    }

    /**
     * Get the value of getVar.
     */
    public function getGetVar(?string $key = null): mixed
    {
        return $this->getvar($key, $this->getVar);
    }

    /**
     * Set the value of getVar.
     */
    public function setGetVar(?array $getVar): self
    {
        $this->getVar = $getVar;
        return $this;
    }

    /**
     * Get the value of postVar.
     */
    public function getPostVar(?string $key = null): mixed
    {
        return $this->getvar($key, $this->postVar);
    }

    /**
     * Set the value of postVar.
     */
    public function setPostVar(?array $postVar): self
    {
        $this->postVar = $postVar;
        return $this;
    }

    /**
     * Get the value of cookiesVar.
     */
    public function getCookiesVar(?string $key = null): mixed
    {
        return $this->getvar($key, $this->cookiesVar);
    }

    /**
     * Set the value of cookiesVar.
     */
    public function setCookiesVar(?array $cookiesVar): self
    {
        $this->cookiesVar = $cookiesVar;
        return $this;
    }

    /**
     * Get the value of filesVar.
     */
    public function getFilesVar(?string $key = null): mixed
    {
        return $this->getvar($key, $this->filesVar);
    }

    /**
     * Set the value of filesVar.
     */
    public function setFilesVar(?array $filesVar): self
    {
        $this->filesVar = $filesVar;
        return $this;
    }

    /**
     * Get the value of serverVar.
     */
    public function getServerVar(?string $key = null): mixed
    {
        if (null != $key) {
            if (!isset($this->serverVar[strtoupper($key)])) {
                return '';
            }
            return $this->serverVar[strtoupper($key)];
        }
        return array_map('strip_tags', $this->serverVar ?? []);
    }

    /**
     * Set the value of serverVar.
     */
    public function setServerVar(?array $serverVar): self
    {
        $this->serverVar = $serverVar;
        return $this;
    }

    protected function getRequesUri() : ?string
    {
        $url = $this->getServerVar('request_uri') ?? '/';
        $position = strpos($url, '?');
        if ($position == false) {
            return $url;
        }
        return substr($url, 0, $position);
    }

    private function getVar(?string $key, array $var) : mixed
    {
        if ($var == []) {
            return [];
        }
        if (null != $key) {
            return $var[$key] ?? null;
        }
        return array_map('strip_tags', $var ?? []);
    }
}