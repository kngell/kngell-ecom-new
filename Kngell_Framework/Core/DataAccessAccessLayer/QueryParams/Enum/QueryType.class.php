<?php

declare(strict_types=1);
enum QueryType: string
{
    case SELECT = 'select';
    case INSERT = 'insert';
    case UPDATE = 'update';
    case UPDATECTE = 'updateCte';
    case DELETE = 'delete';
    case CREATE = 'create';
    case RAW = 'raw';
    case SHOW = 'show';
    case WITHCTE = 'withCte';

    private const METHODS_FLOW_ARY = [
        'select' => ['select', 'fields', 'from', 'table', 'join', 'on', 'where', 'having', 'groupBy', 'orderBy', 'limit', 'offset'],
        'insert' => ['insert', 'fields', 'values'],
        'update' => ['update', 'values', 'where'],
        'updateCte' => ['update', 'fields', 'join', 'values', 'where'],
        'delete' => ['delete', 'from', 'table', 'where'],
        'create' => [],
        'raw' => ['raw', 'join', 'where', 'groupBy', 'orderBy', 'limit', 'offset'],
        'show' => ['show'],
        'withCte' => ['with', 'fields', 'values', 'where'],

    ];

    public function getFlow(): array
    {
        return self::METHODS_FLOW_ARY[$this->value] ?? [];
    }

    public static function get(string $type): ?self
    {
        $type = strtoupper($type);
        foreach (self::cases() as $case) {
            if ($case->name === $type) {
                return $case;
            }
        }
        return null;
    }
}