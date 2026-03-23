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

if (!function_exists('checkPermission')) {

    /**
     * Check permission from current user
     *
     * @param string $permission
     * @return bool
     */
    function checkPermission($permission)
    {
        return auth()->user()->hasPermissionTo($permission);
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
            if (empty($dateTime)) {
                return 'N/A';
            }

            if ($dateTime instanceof \DateTimeInterface) {
                return $dateTime->format('H:i | d/m/Y');
            }

            try {
                $dt = \Carbon\Carbon::parse($dateTime);
                return $dt->format('H:i | d/m/Y');
            } catch (\Throwable $e) {
                return 'N/A';
            }
    }
}

if (!function_exists('customDate')) {

    /**
     * Custom date format method.
     *
     * @param $dateTime
     * @return string
     */
    function customDate($dateTime): string
    {
            if (empty($dateTime)) {
                return 'N/A';
            }

            if ($dateTime instanceof \DateTimeInterface) {
                return $dateTime->format('d/m/Y');
            }

            try {
                $dt = \Carbon\Carbon::parse($dateTime);
                return $dt->format('d/m/Y');
            } catch (\Throwable $e) {
                return 'N/A';
            }
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
