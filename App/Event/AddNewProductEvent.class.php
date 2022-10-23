<?php

declare(strict_types=1);

class AddNewProductEvent extends Event implements EventsInterface
{
    /**
     * Construct.
     *
     * @param object $object
     * @param string|null $eventName
     */
    public function __construct(object $object, ?string $eventName = null, array $params = [])
    {
        parent::__construct($object, $eventName == null ? $this::class : $eventName, $params);
    }

    public function getName(): string
    {
        return $this::class;
    }
}