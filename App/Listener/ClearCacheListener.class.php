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
            foreach ($params['cache'] as $cache) {
                if ($object->getCache()->exists($cache)) {
                    $object->getCache()->delete($cache);
                }
            }
            $object->getSession()->delete(ACTIVE_CACHE_FILES);
        }
        return [];
    }
}