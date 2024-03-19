<?php

declare(strict_types=1);
enum Operators : string
{
    case EQUAL = '=';
    case NOT_EQUAL = '<>';
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
    case LESS_THAN = '<';
    case GREATER_THAN = '>';
    case LESS_THAN_OR_EQUAL_TO = '<=';
    case GREATER_THAN_OR_EQUAL = '>=';

    private const DEFAULT_OPS = 'EQUAL';

    public static function getFromValue(string $op): string|bool
    {
        foreach (self::cases() as $case) {
            if ($case->value === $op) {
                return $case->value;
            }
        }
        return false;
    }

    public static function getOp(string $method, string $baseMethod) : string|bool
    {
        $op = self::DEFAULT_OPS;
        if (str_starts_with($method, 'or') || str_starts_with($method, 'and')) {
            $method = StringUtil::separate($method);
            $method = explode('_', $method);
            $method = $method[1];
        }
        $parts = array_filter(explode($baseMethod, $method));
        if (! empty($parts)) {
            $op = strtoupper(StringUtil::separate(array_pop($parts)));
        }
        foreach (self::cases() as $case) {
            if ($case->name === $op) {
                return $case->value;
            }
        }
        return false;
    }

    public static function exists(mixed $op) : bool
    {
        foreach (self::cases() as $case) {
            if ($case->value === $op) {
                return true;
            }
        }
        return false;
    }
}