<?php

declare(strict_types=1);
class SaveProductsCategoriesListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        $resp = $this->saveCategories(
            $event->getRelatedObject(),
            $event->getParams()['data']
        );
        return [$resp];
    }

    private function productId(ProductsManager $product, array $data) : int
    {
        if ($product->existsLastId()) {
            return $product->getLastID();
        }
        return (int) $data['pdt_id'];
    }

    private function saveCategories(ProductsManager $model, array $data) : ?ProductCategorieManager
    {
        /** @var CollectionInterface */
        $categories = new Collection();
        if (isset($data['categories'])) {
            $catFromJson = json_decode(StringUtil::htmlDecode($data['categories']), true);
            $pdtId = $this->productId($model, $data);
            foreach ($catFromJson as $catID) {
                $categories->add(Container::getInstance()
                    ->make(ProductCategorieEntity::class)->assign(['pdt_id' => (int) $pdtId,
                        'cat_id' => (int) $catID, ]));
            }
            if ($categories->count() > 0) {
                /** @var ProductCategorieManager */
                $model = Container::getInstance()->make(ProductCategorieManager::class);
                $old_categories = $this->oldCategories($model, $pdtId);
                $model->setEntity($old_categories);
                $model->table()->where(['pdt_id' => $pdtId, 'cat_id|in' => 'collection|cat_id'])->return('class');
                return $model->delete()->setEntity($categories)->save();
            }
        }
        return null;
    }

    private function oldCategories(ProductCategorieManager $m, $id) : CollectionInterface
    {
        $m->table()->where(['pdt_id' => $id]);
        $categories = new Collection();
        $old_cats = $m->getAll()->get_results();
        foreach ($old_cats as $old_cat) {
            $categories->add(Container::getInstance()
                ->make(ProductCategorieEntity::class)->assign($old_cat));
        }
        return $categories;
    }
}