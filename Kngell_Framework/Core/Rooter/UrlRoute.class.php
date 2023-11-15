<?php

declare(strict_types=1);

class UrlRoute
{
    private ?string $route = null;
    private array $urlParams = [];

    public function __construct(private RooterHelper $helper, private RequestHandler $request)
    {
        $this->handle();
    }

    /**
     * Get the value of route.
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * Get the value of urlParams.
     */
    public function geturlParams(): array
    {
        return $this->urlParams;
    }

    public function handle(?string $url = null) : self
    {
        $route = DS;
        $url = $url ?? $this->request->getQuery()->get('url');
        $urlInfos = explode(DS, filter_var(rtrim($url, DS)));
        if ($url == 'favicon.ico') {
            $this->urlParams = [$url];
            $this->route = 'assets' . DS . 'getAsset';
        } elseif ($url == '/' || $url == '') {
            $this->urlParams = [];
            $this->route = DS;
        } elseif (str_starts_with($url, 'assets/img')) {
            $this->route = 'assets' . DS . 'getAsset';
            unset($urlInfos[0]);
            $this->urlParams = count($urlInfos) > 0 ? $this->helper->formatUrlArguments(array_values($urlInfos)) : [];
        } else {
            if (isset($urlInfos)) {
                if (strtolower($urlInfos[0]) == 'dy' && count($urlInfos) >= 3) {
                    $route = strtolower($urlInfos[1]) . DS . strtolower($urlInfos[2]);
                    unset($urlInfos[0]);
                    unset($urlInfos[1]);
                    unset($urlInfos[2]);
                } elseif (strtolower($urlInfos[0]) == 'asset' && strtolower($urlInfos[1]) == 'img') {
                    $route = $route . DS . 'getAsset';
                    unset($urlInfos[0]);
                    unset($urlInfos[1]);
                } else {
                    $route = ! empty($urlInfos[0]) ? strtolower($urlInfos[0]) : '';
                    unset($urlInfos[0]);
                    if (isset($urlInfos[1]) && is_string($urlInfos[1])) {
                        $route = $route . DS . $urlInfos[1];
                        unset($urlInfos[1]);
                    }
                }
            }
            $this->route = strtolower($route);
            $this->urlParams = count($urlInfos) > 0 ? $this->helper->formatUrlArguments(array_values($urlInfos)) : [];
        }
        return $this;
    }

    /**
     * Get the value of request.
     */
    public function getRequest() : RequestHandler
    {
        return $this->request;
    }
}