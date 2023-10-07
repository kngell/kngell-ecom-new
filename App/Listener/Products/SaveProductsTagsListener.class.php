<?php

declare(strict_types=1);

class SaveProductsTagsListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        return ['Slack Message here'];
    }
}