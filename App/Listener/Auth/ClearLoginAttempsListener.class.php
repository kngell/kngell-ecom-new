<?php

declare(strict_types=1);
class ClearLoginAttempsListener implements ListenerInterface
{
    public function __construct(private LoginAttemptsManager $loginAttemps)
    {
    }

    public function handle(EventsInterface $event): iterable
    {
        /** @var UserSessionsManager */
        $object = $event->getObject();
        if ($object instanceof UserSessionsEntity) {
            /** @var LoginAttemptsManager */
            $conditions = $this->loginAttemps->table()->where(['userId' => $object->getUserId()])->build();
            $delete = $this->loginAttemps->delete($conditions);

            return [$delete];
        }

        return [];
    }
}