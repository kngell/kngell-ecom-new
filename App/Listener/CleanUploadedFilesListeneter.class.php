<?php

declare(strict_types=1);

class ClearUploadedFilesListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        return ['Slack Message here'];
    }
}