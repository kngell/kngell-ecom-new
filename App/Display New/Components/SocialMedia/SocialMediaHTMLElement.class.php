<?php

declare(strict_types=1);
class SocialMediaHTMLElement extends AbstractHTMLElement
{
    private const MEDIA = [
        'facebook' => ['fab', 'fa-facebook-f'],
        'twitter' => ['fab', 'fa-twitter'],
        'linkedin' => ['fab', 'fa-linkedin-in'],
        //   'google' => [],
        //   'website' => [],
    ];

    public function __construct(?string $template = null, ?array $params = [])
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $html = '';
        $settings = isset($this->params['settings']) ? $this->params['settings']->all() : [];
        foreach (self::MEDIA as $media => $class) {
            $mediaLink = '';
            if (array_key_exists($media . '_link', $settings)) {
                $mediaLink = str_replace('{{link}}', $settings[$media . '_link'], $this->template);
                $mediaLink = str_replace('{{class}}', implode(' ', $class), $mediaLink);
                $html .= $mediaLink;
            }
        }
        return['html', $html];
    }
}