<?php

declare(strict_types=1);
class View extends AbstractView
{
    private object $ressources;
    private string $_head;
    private string $_body;
    private string $_footer;
    private string $_outputBuffer;
    private Token $token;
    private array $properties = [];
    private string $jsTemplate;
    private string $cssTemplate;
    private string $icoTemplate;

    public function __construct(array $viewAry, FilesSystemInterface $fileSyst)
    {
        $this->ressources = json_decode(file_get_contents(APP . 'assets.json'));
        if (!empty($viewAry)) {
            foreach ($viewAry as $key => $value) {
                if ($key != '' && property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        $this->fileSyst = $fileSyst;
        $this->jsTemplate = $this->fileSyst->get(FILES, 'js.php');
        $this->cssTemplate = $this->fileSyst->get(FILES, 'css.php');
        $this->icoTemplate = $this->fileSyst->get(FILES, 'ico.php');
    }

    /**
     * Render View.
     * --------------------------------------------------------------.
     * @param string $viewname
     * @param array $params
     * @return ?string
     */
    public function render(string $viewname = '', array $params = []) : ?string
    {
        if (!empty($viewname)) {
            $this->view_file = preg_replace("/\s+/", '', $viewname);
            if (is_readable(VIEW . strtolower($this->viewPath) . $this->view_file . '.php')) {
                return $this->renderViewContent(VIEW . strtolower($this->viewPath) . $this->view_file . '.php', $params);
            }
            if ($file = $this->viewFile($this->view_file)) {
                $file = array_pop($file);
                if (is_readable($file)) {
                    return $this->renderViewContent($file, $params);
                }
            }
        }
        throw new BaseException("Cette vue n'existe pas", 1);
    }

    public function route(string $route)
    {
        $route = $route == DS ? 'home' : $route;
        return HOST . DS . $route;
    }

    public function addProperties(array $args = []) : void
    {
        if (!empty($args)) {
            foreach ($args as $prop => $value) {
                $this->properties[$prop] = $value;
            }
        }
    }

    public function getProperty(string $name) : mixed
    {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }

    /** @inheritDoc */
    public function asset(string $asset = '', string $ext = '') : string
    {
        $root = isset($asset) ? explode('/', $asset) : [];
        if ($root) {
            $path = '';
            $check = array_shift($root);
            $i = 0;
            foreach ($root as $value) {
                $separator = ($i == count($root) - 1) ? '' : US;
                $path .= $value . $separator;
                $i++;
            }
            return $this->getAsset($check, $path, $asset, $ext);
        }
        return '';
    }

    private function getAsset(string $check, string $path, string $asset, string $ext) : string
    {
        return $this->linkTemplate($asset, match (true) {
            $check == 'img' => $this->getUrl($path, $asset, $ext),
            $check == 'fonts' => HOST ? HOST . FONT . $path : FONT . $asset,
            isset($this->ressources->$asset->$ext) => HOST ? HOST . $this->ressources->$asset->$ext ?? '' : $this->ressources->$asset->$ext ?? '',
            default => ''
        });
    }

    private function getUrl(string $path, string $asset, string $ext) : string
    {
        if ($ext == 'ico') {
            return IMG . $path;
        } else {
            return HOST ? HOST . IMG . $path : IMG . $asset;
        }
    }

    private function linkTemplate(?string $asset = null, string $link = '') : string
    {
        $linkTemplate = '';
        if ($asset) {
            if (str_starts_with($asset, 'css')) {
                $linkTemplate = isset($this->cssTemplate) ? file_get_contents($this->cssTemplate) : '';
            } elseif (str_starts_with($asset, 'js')) {
                $linkTemplate = isset($this->jsTemplate) ? file_get_contents($this->jsTemplate) : '';
            } elseif (str_starts_with($asset, 'commons') && str_ends_with($link, 'js')) {
                $linkTemplate = isset($this->jsTemplate) ? file_get_contents($this->jsTemplate) : '';
            } elseif (str_starts_with($asset, 'commons') && str_ends_with($link, 'css')) {
                $linkTemplate = isset($this->jsTemplate) ? file_get_contents($this->jsTemplate) : '';
            } elseif ($asset == 'img/favicon') {
                $linkTemplate = isset($this->icoTemplate) ? file_get_contents($this->icoTemplate) : '';
            }
        }
        if (!empty($linkTemplate) && $link !== '') {
            $linkTemplate = str_replace('{{link}}', $link . (str_contains($link, 'favicon') ? '.ico' : ''), $linkTemplate);
        }
        return $link !== '' ? $linkTemplate : '';
    }

    private function renderViewContent($view, array $params = []) : ?string
    {
        extract($params, EXTR_SKIP);
        require_once $view;
        $this->start('html');
        $layout = $this->viewFile('layouts' . DS . $this->_layout);
        if ($layout) {
            require_once array_shift($layout);
        }
        $this->end();
        if ($this->webView) {
            return $this->content('html');
        } else {
            return html_entity_decode($this->content('html'));
        }
    }

    private function start(string $type) : void
    {
        $this->_outputBuffer = $type;
        ob_start();
    }

    private function end() : void
    {
        isset($this->_outputBuffer) ? $this->{'_' . $this->_outputBuffer} = ob_get_clean() : '';
    }

    private function content(string $type) : bool|string
    {
        return match ($type) {
            'head' => $this->_head ?? '',
            'body' => $this->_body ?? '',
            'footer' => $this->_footer ?? '',
            'html' => $this->_html ?? '',
            default => false
        };
    }
}