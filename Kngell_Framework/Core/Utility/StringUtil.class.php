<?php

declare(strict_types=1);

final class StringUtil
{
    private function __construct()
    {
    }

    /**
     * @param $text
     * @return string|string[]
     */
    public static function slugify($text): array|string
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text); // replace non letter or digits by -
        $text = trim($text, '-');
        $text = self::translateString($text); // transliterate
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public static function countChar(string $string, string $char) : int
    {
        return substr_count($string, $char);
    }

    /**
     * Convert a string into words, to use for labels and menu names.
     *
     * @param $string - string to replace
     * @param string $atts
     * @return string
     * @since 1.0.0
     */
    public static function justify($string, string $atts = '')
    {
        if (empty($string)) {
            return;
        }
        $search = ['-', '_', '[]', '[', ']'];
        $replace_search = [' ', ' ', '', ' ', ''];
        $str_replace = str_replace($search, $replace_search, $string);
        /* Capitalize the first letter */
        if ('ucwords' == $atts) {
            return ucwords(str_replace($search, $replace_search, $string));
        }
        if ('strtolower' == $atts) {
            return strtolower(str_replace($search, $replace_search, $string));
        }
    }

    /**
     * pluralize the string if necessary.
     *
     * @return 		string
     * @uses 		strlen & substr
     * @param 		$string
     */
    public static function pluralize($string): string
    {
        $last = $string[strlen($string) - 1];
        if ($last == 'y') {
            $cut = substr($string, 0, -1);
            $plural = $cut . 'ies'; //convert y to ies
        } else {
            $plural = $string . 's'; // just attach an s
        }
        return $plural;
    }

    /**
     * @param $string
     * @param bool $full
     * @return bool|string
     */
    public static function capitalize($string, bool $full = false): bool|string
    {
        if (! empty($string)) {
            // transliterate
            $text = self::translateString($string);
            $text = $full ? strtoupper($text) : ucwords($text);
            //$text = preg_replace('~[^-\w]+~', '', $text);
            if (empty($text)) {
                return 'n-a';
            }
            return $text;
        }
        return false;
    }

    public static function studlyCaps(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    public static function camelCase(string $string) : string
    {
        return lcfirst(self::studlyCaps($string));
    }

    public static function separate(string $str)
    {
        $separator = '_';
        if (! is_scalar($str) && ! is_array($str)) {
            return $str;
        }
        if (defined('PREG_BAD_UTF8_OFFSET_ERROR') && preg_match('/\pL/u', 'a') == 1) {
            $pattern = ['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'];
            $replacement = [$separator . '\1', $separator . '\1'];
        } else {
            $pattern = ['#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#', '#(?<=(?:[a-z0-9]))([A-Z])#'];
            $replacement = ['\1' . $separator . '\2', $separator . '\1'];
        }
        return strtolower(preg_replace($pattern, $replacement, $str));
    }

    /**
     * Regular expression function that replaces spaces between words with hyphens.
     *
     * @param string $str - the string to convert
     * @return bool|string
     */
    public static function slugToUrl(string $str): bool|string
    {
        if (empty($str)) {
            return false;
        }
        return preg_replace('/[^A-Za-z0-9-]+/', '-', $str);
    }

    public static function endsWith(mixed $haystack, mixed $needle)
    {
        return str_ends_with($haystack, $needle);
    }

    public static function isBlank(string $str) : bool
    {
        $str = trim($str);
        return ! isset($str) || $str === '';
    }

    public static function getLastCharacter(string $str) : string
    {
        return ! self::isBlank($str) ? substr($str, -1) : '';
    }

    public static function addTrailingChar(string $char, string $str) : string
    {
        if (self::endsWith($str, $char)) {
            return $str;
        }
        return $str . $char;
    }

    public static function strToUrl(string $url)
    {
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, '-');
        $url = iconv('utf-8', 'us-ascii//TRANSLIT', $url);
        $url = strtolower($url);
        return preg_replace('~[^-a-z0-9_]+~', '', $url);
    }

    /**
     * Get Html Decode texte
     * ========================================================.
     * @param string $str
     * @return string
     */
    public static function htmlDecode(string $str) : string
    {
        return ! empty($str) ? htmlspecialchars_decode(html_entity_decode($str), ENT_QUOTES) : '';
    }

    private static function translateString(string $string): string
    {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        if ($text) {
            return $text;
        }
    }
}