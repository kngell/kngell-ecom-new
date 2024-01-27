<?php

declare(strict_types=1);

class SaveProductsExternalFieldsListener
{
    protected array $data;
    protected ProductsManager $model;
    protected object $fieldModel;
    protected string $entity;
    protected string $field;
    protected string $entityPdtId;
    protected string $entityFieldId;

    public function __construct(array $params, ProductsManager $model, object $fieldModel, string $field)
    {
        $this->data = $params['data'];
        $this->model = $model;
        $this->fieldModel = $fieldModel;
        $this->entity = $fieldModel->getEntity()::class;
        $this->field = $params[$field]['name'];
        $this->entityPdtId = $params[$field]['pdt'];
        $this->entityFieldId = $params[$field]['rel'];
    }

    public function saveField()
    {
        /** @var CollectionInterface */
        $newItems = new Collection();
        if (isset($this->data[$this->field]) && is_array($this->data[$this->field])) {
            $pdtId = $this->productId($this->model, $this->data);
            foreach ($this->data[$this->field] as $id) {
                if ($id != '') {
                    $newItems->add((new $this->entity())->assign([$this->entityPdtId => (int) $pdtId,
                        $this->entityFieldId => (int) $id, ]));
                }
            }
            if ($newItems->count() > 0) {
                $fieldModel = $this->oldItems($this->fieldModel, $pdtId, $this->data[$this->field]);
                $fieldModel->table()->where([$this->entityPdtId => $pdtId, $this->entityFieldId . '|in' => 'collection|' . $this->entityFieldId])->return('class');
                return $fieldModel->setEntity($newItems)->save();
            }
        }
        return null;
    }

    protected function productId(ProductsManager $product, array $data) : int
    {
        if ($product->existsLastId()) {
            return $product->getLastID();
        }
        return (int) $data['pdtId'];
    }

    private function oldItems(object $m, int $id, array $item_list) : Model
    {
        $oldItems = new Collection();
        $m->table()->where([$this->entityPdtId => $id, $this->entityFieldId . '|in' => $item_list]);
        $olds = $m->getAll()->get_results();
        foreach ($olds as $old) {
            $oldItems->add((new $this->entity())->assign($old));
        }
        return ! empty($olds) ? $m->setEntity($oldItems)->delete() : $m->setEntity($oldItems);
    }
}
