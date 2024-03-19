<?php

declare(strict_types=1);
abstract class AbstractHTMLElement extends AbstractHTMLPage
{
    protected array $params = [];

    public function __construct(?string $template = null, array $params = [])
    {
        parent::__construct($template);
        $this->params = $params;
    }

    abstract public function display() : array;

    /**
     * Get the value of params.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set the value of params.
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    protected function media(object $obj, ?string $defaultMadia = null, bool $string = true) : string|array
    {
        if (isset($obj->media) && ! is_null($obj->media)) {
            $media = ! is_array($obj->media) ? unserialize($obj->media) : $obj->media;
            if (is_array($media) && count($media) > 0) {
                $all_media = [];
                foreach ($media as $med) {
                    $all_media[] = str_starts_with($med, IMG) ? $med : ImageManager::asset_img($med);
                }
                return $string ? $all_media[0] : $all_media;
            }
        }
        if ($defaultMadia !== null) {
            return ImageManager::asset_img($defaultMadia);
        }
        return '';
    }
}