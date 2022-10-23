<?php

declare(strict_types=1);

class ClearCacheListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        $params = $event->getParams();
        /** @var Controller */
        $object = $event->getObject();
        if (isset($params['cache'])) {
            $cache = $params['cache'];
            $object->getCache()->delete($cache);
        }
        return [];
    }
}