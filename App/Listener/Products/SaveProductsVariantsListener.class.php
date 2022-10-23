<?php

declare(strict_types=1);
class SaveProductsVariantsListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        /** @var UserCartController */
        $object = $event->getObject();
        return [];
    }
}
