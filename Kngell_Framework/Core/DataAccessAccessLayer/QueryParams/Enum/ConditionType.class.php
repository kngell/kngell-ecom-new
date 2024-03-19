<?php

declare(strict_types=1);

enum ConditionType : string
{
    case WHERE = 'where';
    case ON = 'on';
    case HAVING = 'having';
    case SET = 'set';

    private const CONDITION_FAMILY = [
        'where' => ['where',  'orWhere',  'whereNotIn', 'whereIn', 'andWhere', 'whereEquals', 'whereNotEquals', 'whereLessThan', 'whereGreaterThan', 'whereLessThanOrEqualTo', 'whereGreaterThanOrEqualTo'],
        'on' => ['on', 'orOn', 'andOn', 'onNotIn', 'onIn', 'onEquals', 'onNotEquals'],
        'having' => ['having', 'havingNotIn', 'orHaving', 'andHaving', 'havingIn', 'havingEquals', 'havingNotEquals'],
        'set' => ['set'],
    ];

    public function getFamily(): array
    {
        return self::CONDITION_FAMILY[$this->value] ?? [];
    }

    public static function getFamilyMethods(string $method) : array
    {
        $fm = [];
        foreach (self::CONDITION_FAMILY as $family) {
            $fm[] = array_map(function ($var) use ($method) {
                return str_contains($var, $method) ? $var : '';
            }, $family);
        }
        return array_filter(ArrayUtil::flatten_without_keys($fm));
    }

    public static function get(string $type): ?string
    {
        foreach (self::CONDITION_FAMILY as $statement => $family) {
            if (in_array($type, $family)) {
                return ' ' . self::from($statement)->name . ' ';
            }
        }
        throw new BadQueryArgumentException("the method - $type - is not registered yet!");
    }

    public static function allMethods() : array
    {
        $fm = [];
        foreach (self::CONDITION_FAMILY as $family) {
            $fm[] = array_merge($fm, $family);
        }
        return $fm;
    }
}