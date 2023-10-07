<?php

declare(strict_types=1);

class UrlRoute
{
    private ?string $route = null;
    private array $urlParams = [];
    private ?RooterHelper $helper;

    public function __construct(?RooterHelper $helper = null)
    {
        $this->helper = $helper;
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

    public function handle(RequestHandler $request) : self
    {
        $route = DS;
        $url = $request->getQuery()->get('url');
        if ($url == 'favicon.ico') {
            $this->urlParams = [$url];
            $route = 'assets' . DS . 'getAsset';
        } elseif ($url == '/' || $url == '') {
            $this->urlParams = [];
            $this->route = DS;
        } else {
            $urlInfos = explode(DS, filter_var(rtrim($url, DS)));
            if (isset($urlInfos)) {
                if (strtolower($urlInfos[0]) == 'dy' && count($urlInfos) >= 3) {
                    $route = strtolower($urlInfos[1]) . DS . strtolower($urlInfos[2]);
                    unset($urlInfos[0]);
                    unset($urlInfos[1]);
                    unset($urlInfos[2]);
                } elseif (strtolower($urlInfos[0]) == 'asset' && strtolower($urlInfos[1]) == 'img') {
                    $route = $route . DS . 'getAsset';
                    unset($urlroute[0]);
                    unset($urlroute[1]);
                } else {
                    $route = !empty($urlInfos[0]) ? strtolower($urlInfos[0]) : '';
                    unset($urlInfos[0]);
                }
            }

            $urlInfos = array_values($urlInfos);
            // if (!empty($urlInfos)) {
            //     if ($route == 'assets' && $urlInfos[0] == 'img') {
            //         unset($urlInfos[0]);
            //         $route = $route . DS . 'getAsset';
            //     } else {
            //         $route = $route . DS . strtolower($urlInfos[0]);
            //         unset($urlInfos[0]);
            //     }
            // }

            $this->urlParams = count($urlInfos) > 0 ? $this->helper->formatUrlArguments(array_values($urlInfos)) : [];
            $this->route = strtolower($route);
        }
        return $this;
    }
}