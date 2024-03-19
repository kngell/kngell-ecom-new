<?php

declare(strict_types=1);
class DeleteCacheFileListener implements ListenerInterface
{
    public function handle(EventsInterface $event): ?iterable
    {
        /** @var UserCartController */
        $object = $event->getObject();
        $cache = $object->getUserCart()->getCache();
        $cachedFiles = $object->getUserCart()->getCachedFiles();
        if ($cache->exists($cachedFiles['user_cart'])) {
            $cache->delete($cachedFiles['user_cart']);
        }
        return null;
    }
}