<?php

declare(strict_types=1);
// Helper class for visitors
class H_visitors
{
    //=======================================================================
    //Get visitors Data
    //=======================================================================
    //Get Ip
    public static function getIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        }
    }

    //Get ip data
    public static function getIpData($ip = '')
    {
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        if ($query && $query['status'] == 'success') {
            return $query;
        }
        return $query['query'];
    }

    //Key format for API geoplugins
    public static function new_geoplugin_keys()
    {
        return ['geoplugin_request' => 'ipAddress', 'geoplugin_status' => 'statusCode', 'geoplugin_delay' => 'delay', 'geoplugin_credit' => 'credit', 'geoplugin_city' => 'cityName', 'geoplugin_regionCode' => 'regionCode', 'geoplugin_regionName' => 'regionName', 'geoplugin_areaCode' => 'areaCode', 'geoplugin_dmaCode' => 'dmaCode', 'geoplugin_countryCode' => 'countryCode', 'geoplugin_countryName' => 'countryName',   'geoplugin_inEU' => 'inEU', 'geoplugin_euVATrate' => 'euVATrate', 'geoplugin_continentCode' => 'continentCode',   'geoplugin_continentName' => 'continentName', 'geoplugin_latitude' => 'latitude', 'geoplugin_longitude' => 'longitude',   'geoplugin_locationAccuracyRadius' => 'locationAccuracyRadius', 'geoplugin_timezone' => 'timeZone', 'geoplugin_currencyCode' => 'currencyCode', 'geoplugin_currencySymbol' => 'currencySymbol',   'geoplugin_currencySymbol_UTF8' => 'currencySymbol_UTF8', 'geoplugin_currencyConverter' => 'currencyConverter'];
    }

    //Key format for IpAPI
    public static function new_IpAPI_keys() : array
    {
        return [
            'asn' => 'asn',
            'city' => 'cityName',
            'continent_code' => 'continentCode',
            'country' => 'country',
            'country_area' => 'countryArea',
            'country_calling_code' => 'countryCallingCode',
            'country_capital' => 'countryCapital',
            'country_code' => 'countryCode',
            'country_code_iso3' => 'countryCodeIso3',
            'country_name' => 'countryName',
            'country_population' => 'countryPopulation',
            'currency' => 'currency',
            'currency_name' => 'currencyName',
            'in_eu' => 'inEu',
            'ip' => 'ipAddress',
            'languages' => 'languages',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'network' => 'network',
            'org' => 'org',
            'postal' => 'postalCode',
            'region' => 'region',
            'region_code' => 'regionCode',
            'timezone' => 'timezone',
            'utc_offset' => 'utcOffset',
            'version' => 'version',
        ];
    }
}