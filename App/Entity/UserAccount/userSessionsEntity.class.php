<?php

declare(strict_types=1);

class UserSessionsEntity extends Entity
{
    /** @id */
    private int $usId;
    /** @Remember Me Cookie */
    private ?string $rememberMeCookie;
    private ?string $sessionToken;
    private ?string $userId;
    private ?string $userAgent;
    private ?string $userCookie;
    private ?string $email;
    private ?string $password;
    private ?string $createdAt;
    private ?string $updatedAt;
    private int $expiry;
    private ?string $userData;

    public function __construct()
    {
        // $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of usID.
     */
    public function getUsId() : int
    {
        return $this->usId;
    }

    /**
     * Get the value of userID.
     */
    public function getUserId() : string
    {
        return $this->userId;
    }

    /**
     * Set the value of userID.
     *
     * @return  self
     */
    public function setUserId($userID) : self
    {
        $this->userId = $userID;

        return $this;
    }

    /**
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @return  self
     */
    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    public function delete(?string $field = null) : self
    {
        unset($this->$field);

        return $this;
    }

    /**
     * Get the value of password.
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     *
     * @return  self
     */
    public function setPassword(string $password) : self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of usID.
     *
     * @return  self
     */
    public function setUsId(int $usID) : self
    {
        $this->usId = $usID;

        return $this;
    }

    /**
     * Get the value of rememberMeCookie.
     */
    public function getRememberMeCookie() : string
    {
        return $this->rememberMeCookie;
    }

    /**
     * Set the value of rememberMeCookie.
     *
     * @return  self
     */
    public function setRememberMeCookie(string $rememberMeCookie) : self
    {
        $this->rememberMeCookie = $rememberMeCookie;

        return $this;
    }

    /**
     * Get the value of sessionToken.
     */
    public function getSessionToken() : string
    {
        return $this->sessionToken;
    }

    /**
     * Set the value of sessionToken.
     *
     * @return  self
     */
    public function setSessionToken(string $sessionToken) : self
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }

    /**
     * Get the value of userAgent.
     */
    public function getUserAgent() : string
    {
        return $this->userAgent;
    }

    /**
     * Set the value of userAgent.
     *
     * @return  self
     */
    public function setUserAgent(string $userAgent) : self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get the value of userCookie.
     */
    public function getUserCookie() : string
    {
        return $this->userCookie;
    }

    /**
     * Set the value of userCookie.
     *
     * @return  self
     */
    public function setUserCookie(string $userCookie) : self
    {
        $this->userCookie = $userCookie;

        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt() : ?string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     *
     * @return  self
     */
    public function setCreatedAt(?string $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of expiry.
     */
    public function getExpiry(): int
    {
        return $this->expiry;
    }

    /**
     * Set the value of expiry.
     */
    public function setExpiry(int $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }

    /**
     * Get the value of userData.
     */
    public function getUserData(): ?string
    {
        return $this->userData;
    }

    /**
     * Set the value of userData.
     */
    public function setUserData(?string $userData): self
    {
        $this->userData = $userData;

        return $this;
    }
}