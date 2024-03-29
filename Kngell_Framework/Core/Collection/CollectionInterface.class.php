<?php

declare(strict_types=1);

interface CollectionInterface extends Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Returns all the items within the collection.
     *
     * @return array
     */
    public function all(): array;

    public function first(callable|null $callback = null, $default = null) : mixed;

    public function last() : mixed;

    /**
     * Checks whether a given key exists within the collection.
     *
     * @param mixed $key
     * @return bool
     */
    public function has(mixed $key): bool;

    /**
     * Returns all the keys of the collection items.
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Run a map over each items.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): static;

    public function avg();

    public function shuffle() : static;

    /**
     * Calculates the sum of values within the specified array.
     *
     * @param array $array
     * @return static
     */
    public function sum(): static;

    public function min();

    public function max();

    /**
     * Create an collection with the specified ranges.
     *
     * @param mixed $from
     * @param mixed $to
     * @return static
     */
    public function range($from, $to): static;

    /**
     * Merge the collection with the given argument.
     *
     * @param mixed $items
     * @return static
     */
    public function merge(mixed $items): static;

    /**
     * Recursively merge the collection with the given argument.
     *
     * @param mixed $items
     * @return static
     */
    public function mergeRecursive(mixed $items): static;

    /**
     * Pop an element off the end of the item collection.
     *
     * @return mixed
     */
    public function pop();

    /**
     * Push elements on the end of the collection items.
     *
     * @param mixed ...$values
     * @return self
     */
    public function push(...$values): self;

    /**
     * Returns the items collection in reverse order.
     *
     * @return static
     */
    public function reverse(): static;

    /**
     * Shift the first element of the collection items.
     *
     * @return mixed
     */
    public function shift(): mixed;

    /**
     * Extract a slice of the collection items.
     *
     * @param [type] $offset
     * @param [type] $length
     * @return static
     */
    public function slice(int $offset, $length = null): static;

    /**
     * Returns the values of the collection items.
     *
     * @return static
     */
    public function values(): static;

    /**
     * Count the number of items within the collection items.
     *
     * @return int
     */
    public function size(): int;

    /**
     * Add an item to the collection.
     *
     * @param mixed $item
     * @return self
     */
    public function add(mixed $item): self;

    /**
     * Remove the item from the collection.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;

    public function removeByValue(mixed $value) : void;

    public function updateValue(mixed $oldValue, mixed $NewValue) : void;

    /**
     * Removes duplicate entry from the collection items.
     *
     * @return static
     */
    public function unique(): static;

    /**
     * Returns the items in the collection which is not within the specified index array.
     *
     * @param mixed $items
     * @return static
     */
    public function diff(mixed $items): static;

    /**
     * Returns the items in the collection which is not within the the specified associative array.
     *
     * @param mixed $items
     * @return static
     */
    public function diffAssoc(mixed $items): static;

    /**
     * Returns the items in the collection whose keys and values is not within the
     * specified associative array, using the callback.
     *
     * @param mixed $items
     * @param callable $callback
     * @return static
     */
    public function diffAssocUsing(mixed $items, callable $callback): static;

    /**
     * Returns the items in the collection whose keys in not within the specified
     * index array.
     *
     * @param mixed $items
     * @return static
     */
    public function diffKeys(mixed $items): static;

    /**
     * Returns the items in the collection whose keys in not within the specified
     * index array, using the callback.
     *
     * @param mixed $items
     * @param callable $callback
     * @return static
     */
    public function diffKeysUsing(mixed $items, callable $callback): static;

    public function filter(?callable $callback = null): static;

    public function valueExists(mixed $value) : bool;

    public function getObjectWithValue(string $id, mixed $value) : array;

    public function isEmpty() : bool;

    public function addAll(array $parameters) : self;

    public function clear(string $key): void;

    public function getWithDefault(string $key, mixed $defaultValue) : mixed;

    public function get(mixed $key): mixed;

    public function replace(mixed $newValues, mixed $oldValue) : void;
}