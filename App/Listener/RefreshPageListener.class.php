<?php

declare(strict_types=1);

class RefreshPageListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();
        if ($object->getRequest()->isAjax()) {
            $object->jsonResponse(['result' => 'success', 'msg' => 'redirect']);
        }
        return ['Slack Message here'];
    }
}