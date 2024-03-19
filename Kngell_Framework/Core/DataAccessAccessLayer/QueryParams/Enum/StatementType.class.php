<?php

declare(strict_types=1);
enum StatementType : string
{
    case FieldsStatement = 'fields';
    case ValuesStatement = 'values';
    case ConditionStatement = 'condition';
    case GroupAndSortStatement = 'groupSort';
    case CounterStatement = 'count';
    case QueryStatement = 'common';

    public static function get(string $method): ?self
    {
        $method = strtolower($method);
        foreach (self::statementMethod() as $stValue => $family) {
            $family = ! is_array($family[0]) ? [$family] : $family;
            if (in_array($method, array_merge(...$family))) {
                return self::from($stValue);
            }
        }
        return null;
    }

    public function parameters(string $key) : string
    {
        $params = [
            'fields' => FieldsParameters::class,
            'values' => ValuesParameters::class,
            'condition' => ConditionParameters::class,
            'groupSort' => GroupAndSortParameters::class,
            'count' => CountParameters::class,
            'common' => QueryParameters::class,
            // 'values' => ValuesParameters::class,
        ];
        return $params[$key];
    }

    private static function statementMethod(): array
    {
        return [
            'fields' => ['fields'],
            'values' => ['values'],
            'condition' => ConditionType::allMethods(),
            'groupSort' => ['groupby', 'orderby'],
            'count' => ['limit', 'offset'],
            'common' => ['select', 'join', 'from', 'table', 'insert', 'update', 'delete', 'raw', 'withcte'],
        ];
    }

    private static function statementValue(string $basemethod) : string
    {
        foreach (self::statementMethod() as $stValue => $family) {
            if (in_array($basemethod, array_merge(...$family))) {
                return $stValue;
            }
        }
    }
}