<?php

declare(strict_types=1);
class SaveWarehouseProductsListener implements ListenerInterface
{
    public function __construct(private WarehouseProductManager $fieldObj, protected string $field = 'warehouse')
    {
    }

    public function handle(EventsInterface $event): iterable
    {
        $params = $event->getParams();
        $params[$this->field] = [
            'name' => $this->field,
            'pdt' => 'product_id',
            'rel' => 'wh_id',
        ];
        $savefield = new SaveProductsExternalFieldsListener(
            $params,
            $event->getRelatedObject(),
            $this->fieldObj,
            $this->field
        );
        return [$savefield->saveField()];
    }
}