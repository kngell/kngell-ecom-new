<?php

declare(strict_types=1);
class SettingsElement extends AbstractHTMLElement
{
    public function __construct(?string $template = null, ?array $params = [])
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $template = '';
        if (isset($this->params['settings']) && ! empty($this->params['settings'])) {
            $settings = $this->params['settings'];
            $template = str_replace('{{address}}', $settings->offsetGet('site_address') ?? '', $this->template);
            $template = str_replace('{{phone}}', $settings->offsetGet('site_phone') ?? '', $template);
        }
        return ['settings', $template];
    }
}