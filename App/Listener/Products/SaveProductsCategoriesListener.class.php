<?php

declare(strict_types=1);
class SaveProductsCategoriesListener implements ListenerInterface
{
    public function __construct(private ProductCategorieManager $fieldObj, protected string $field = 'categorieItem')
    {
    }

    public function handle(EventsInterface $event): iterable
    {
        $params = $event->getParams();
        $params[$this->field] = [
            'name' => $this->field,
            'pdt' => 'pdtId',
            'rel' => 'cat_id',
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
