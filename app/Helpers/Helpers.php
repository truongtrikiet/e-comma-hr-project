<?php

if (!function_exists('checkPermissions')) {

    /**
     * Check permissions from current user
     *
     * @param array $permissions
     * @return bool
     */
    function checkPermissions($permission)
    {
        return auth()->user()->hasAnyPermission($permission);
    }
}

if (!function_exists('customDateFormat')) {

    /**
     * Custom date format method.
     *
     * @param $dateTime
     * @return string
     */
    function customDateFormat($dateTime): string
    {
        return date_format($dateTime, 'H:i | d/m/Y') ?? 'N/A';
    }
}

if (!function_exists('customPriceFormat')) {

    /**
     * Check permission from current user
     *
     * @param $value
     * @return string
     */
    function customPriceFormat($value): string
    {
        return number_format($value, 0, ',', '.');
    }
}
