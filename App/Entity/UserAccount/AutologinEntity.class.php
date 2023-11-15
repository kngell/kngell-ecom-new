<?php

declare(strict_types=1);

class AutologinEntity extends Entity
{
    /** @id */
    private int $alId;
    private int $userId;
    private string $token;
    private string $createdAt;
    private string $updatedAt;
    private string $userData;
    private int $used;

    /**
     * Get the value of alId.
     */
    public function getAlId(): int
    {
        return $this->alId;
    }

    /**
     * Set the value of alId.
     */
    public function setAlId(int $alId): self
    {
        $this->alId = $alId;

        return $this;
    }

    /**
     * Get the value of userId.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId.
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of token.
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the value of token.
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of userData.
     */
    public function getUserData(): string
    {
        return $this->userData;
    }

    /**
     * Set the value of userData.
     */
    public function setUserData(string $userData): self
    {
        $this->userData = $userData;

        return $this;
    }

    /**
     * Get the value of used.
     */
    public function getUsed(): int
    {
        return $this->used;
    }

    /**
     * Set the value of used.
     */
    public function setUsed(int $used): self
    {
        $this->used = $used;

        return $this;
    }
}