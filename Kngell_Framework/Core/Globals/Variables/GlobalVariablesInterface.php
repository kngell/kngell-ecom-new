<?php

declare(strict_types=1);

interface GlobalVariablesInterface
{
    /**
     * Get Variable.
     *
     * @param string|null $key
     * @return mixed
     */
    public function getGetVar(?string $key = null): mixed;

    /**
     * Post Variable.
     *
     * @param string|null $key
     * @return mixed
     */
    public function getPostVar(?string $key = null): mixed;

    /**
     * Cookie Variable.
     *
     * @param string|null $key
     * @return mixed
     */
    public function getCookiesVar(?string $key = null): mixed;

    /**
     * File Variable.
     *
     * @param string|null $key
     * @return mixed
     */
    public function getFilesVar(?string $key = null): mixed;

    /**
     * Server Variable.
     *
     * @param string|null $key
     * @return mixed
     */
    public function getServerVar(?string $key): mixed;
}