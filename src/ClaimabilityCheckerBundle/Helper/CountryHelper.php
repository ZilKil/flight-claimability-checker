<?php

declare(strict_types = 1);

namespace ClaimabilityCheckerBundle\Helper;


class CountryHelper
{
    private const EU_COUNTRIES = [
        'BE',
        'BG',
        'CZ',
        'DK',
        'DE',
        'EE',
        'IE',
        'EL',
        'ES',
        'FR',
        'HR',
        'IT',
        'CY',
        'LV',
        'LT',
        'LU',
        'HU',
        'MT',
        'NL',
        'AT',
        'PL',
        'PT',
        'RO',
        'SI',
        'SK',
        'FI',
        'SE',
        'UK',
    ];

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    public static function isCountryInEu(string $countryCode): bool
    {
        return in_array($countryCode, self::EU_COUNTRIES);
    }
}
