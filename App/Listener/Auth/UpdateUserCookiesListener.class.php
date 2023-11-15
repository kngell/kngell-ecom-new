<?php

declare(strict_types=1);

class UpdateUserCokkiesListener implements ListenerInterface
{
    /**
     * @param EventsInterface $event
     */
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();
        if ($object instanceof UserSessionsEntity) {
            /** @var UsersManager */
            $user = Container::getInstance()->make(UsersManager::class)->assign([
                'userId' => (int) $object->getUserID(),
                'userCookie' => $object->getUserCookie(),
            ])->save();

            return [$user];
        }
        return [];
    }
}