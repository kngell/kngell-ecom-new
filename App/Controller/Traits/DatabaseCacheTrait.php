<?php

declare(strict_types=1);

trait DatabaseCacheTrait
{
    public function getCachedData(?string $fileName = null, array $cm = [], array $params = [], int $time = 20) : Object
    {
        $cacheFile = $this->cachedFiles[$fileName] ?? $fileName;
        if ((!is_array($cm) || !is_array($params)) || empty($cm)) {
            throw new BaseInvalidArgumentException('Paramètres collecte des données invalides', 1);
        }
        if (!$this->cache->exists($cacheFile)) {
            $this->cache->set($cacheFile, call_user_func_array($cm, !empty($params) ? $params : []), $time);
        }
        if ($this->session->exists(ACTIVE_CACHE_FILES)) {
            if (!in_array($cacheFile, $this->session->get(ACTIVE_CACHE_FILES))) {
                $activeCacheFile = $this->session->get(ACTIVE_CACHE_FILES);
                $activeCacheFile[] = $cacheFile;
                $this->session->set(ACTIVE_CACHE_FILES, $activeCacheFile);
            }
        } else {
            $this->session->set(ACTIVE_CACHE_FILES, [$cacheFile]);
        }
        return $this->cache->get($cacheFile);
    }

    public function getUserCart() : CollectionInterface
    {
        return $this->getCachedData('user_cart', [$this->model(CartManager::class), 'getUserCart']);
    }

    public function getSettings() : object
    {
        return $this->getCachedData('settings', [$this->model(SettingsManager::class), 'getSettings']);
    }

    protected function getProducts(int|string|null $brand = null, ?string $cache = null) : CollectionInterface
    {
        return $this->getCachedData($cache ?? __FUNCTION__, [$this->model(ProductsManager::class), 'getProducts'], [$brand]);
    }

    protected function getUnits(int|string|null $brand = null, ?string $cache = null) : CollectionInterface
    {
        return $this->getCachedData($cache ?? __FUNCTION__, [$this->model(UnitsManager::class), 'all'], [$brand]);
    }

    protected function getBackBorder(int|string|null $brand = null, ?string $cache = null) : CollectionInterface
    {
        return $this->getCachedData($cache ?? __FUNCTION__, [$this->model(BackBorderManager::class), 'all'], [$brand]);
    }

    protected function getCompany(int|string|null $brand = null, ?string $cache = null) : CollectionInterface
    {
        return $this->getCachedData($cache ?? __FUNCTION__, [$this->model(CompanyManager::class), 'all'], [$brand]);
    }

    protected function getWarehouse(int|string|null $brand = null, ?string $cache = null) : CollectionInterface
    {
        return $this->getCachedData($cache ?? __FUNCTION__, [$this->model(WarehouseManager::class), 'all'], [$brand]);
    }

    protected function getCategories(int|string|null $brand = null, ?string $cache = null) : CollectionInterface
    {
        return $this->getCachedData($cache ?? __FUNCTION__, [$this->model(CategoriesManager::class), 'all'], [$brand]);
    }

    protected function getSingleProduct(?string $slug) : ?object
    {
        $cacheKey = StringUtil::studlyCaps($slug);

        return $this->getCachedData($cacheKey, [$this->model(ProductsManager::class), 'getSingleProduct'], [$slug], 20);
    }

    protected function getSliders() : CollectionInterface
    {
        return $this->getCachedData('sliders', [$this->model(SlidersManager::class), 'all']);
    }

    protected function getShippingClass() : CollectionInterface
    {
        return $this->getCachedData('shipping_class', [$this->model(ShippingClassManager::class), 'getShippingClass']);
    }

    protected function getOrderList(array $params = []) : CollectionInterface
    {
        if (AuthManager::isUserLoggedIn()) {
            return $this->container(OrdersManager::class)->assign([
                'ord_userId' => $this->session->get(CURRENT_USER_SESSION_NAME)['id'],
            ])->AllWithSearchAndPagin($params);
        }

        return new Collection([]);
    }

    protected function getCustomerEntity() : CustomerEntity
    {
        $customer = $this->model(Customer::class)->get();
        if ($this->session->exists(CURRENT_USER_SESSION_NAME)) {
            return $this->getCachedData(
                'customer' . $this->session->get(CURRENT_USER_SESSION_NAME)['id'],
                [$customer, 'getEntity']
            );
        }
        return new CustomerEntity();
    }

    protected function getCustomerEntityFromSession() : ?CustomerEntity
    {
        if ($this->session->exists(CHECKOUT_PROCESS_NAME)) {
            return unserialize($this->session->get(CHECKOUT_PROCESS_NAME));
        }

        return null;
    }

    protected function getUserAccount(array $params = []) : ?UserAccount
    {
        if ($this->session->exists(CURRENT_USER_SESSION_NAME)) {
            $file = 'user_account' . $this->session->get(CURRENT_USER_SESSION_NAME)['id'];

            return $this->getCachedData($file, [$this->model(UserAccount::class), 'get'], [$params]);
        }

        return null;
    }
}
