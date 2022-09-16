<?php

declare(strict_types=1);
use Brick\Math\RoundingMode;
use Brick\Money\Context\AutoContext;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;

class MoneyManager
{
    /** @var static */
    protected static $inst;

    /**
     * Get container instance
     * ====================================================================================================.
     * @return static
     */
    public static function getInstance(): static
    {
        if (is_null(static::$inst)) {
            static::$inst = new static();
        }

        return self::$inst;
    }

    public function getPrice(mixed $price, string $currencyCode = 'EUR')
    {
        // return Money::of($price, $currencyCode, new AutoContext())->getAmount();
        return Money::ofMinor($price, $currencyCode);
    }

    public function setPrice(mixed $price) : string
    {
        if (!empty($price) && isset($price)) {
            return (string) $this->getAmount($price);
        }

        return '0';
    }

    public function setCurrency(Money $price) : string
    {
        return $price->getCurrency()->getCurrencyCode();
    }

    public function getAmount(string $p = '')
    {
        $this->isEmpty($p);

        return Money::of($p, 'EUR', new AutoContext());
    }

    public function getCustomAmt(string $p, int $context)
    {
        $this->isEmpty($p);

        return Money::of($p, 'EUR', new CustomContext($context), RoundingMode::DOWN);
    }

    public function getFormatedAmount(string $p = '')
    {
        $this->isEmpty($p);

        return Money::of($p, 'EUR', new CustomContext(2), RoundingMode::DOWN)->formatTo('fr_FR');
    }

    public function getIntAmount(string $p = '') : int
    {
        $this->isEmpty($p);

        return Money::of($p, 'EUR', new CustomContext(2), RoundingMode::UP)->getMinorAmount()->toInt();
    }

    public function intFromMoney(Money $money) : int
    {
        return $money->getMinorAmount()->toInt();
    }

    public function roundedDown() : int
    {
        return RoundingMode::DOWN;
    }

    private function isEmpty(string $p) : int|bool
    {
        if (empty($p)) {
            return 0;
        }

        return true;
    }
}
