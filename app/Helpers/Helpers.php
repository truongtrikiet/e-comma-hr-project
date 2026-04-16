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

if (!function_exists('customContentFormat')) {

    /**
     * Format price and limit content length
     *
     * @param mixed $value
     * @return string
     */
    function customContentFormat($value): string
    {
        return mb_strimwidth(strip_tags($value), 0, 100, '...');
    }
}

if (!function_exists('formatDateDMY')) {

    /**
     *
     * @param string|\DateTime $date
     * @return string
     */
    function formatDateDMY($date)
    {
        if (empty($date)) {
            return '';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->format('d-m-Y');
    }
}

if (!function_exists('extractImageUrls')) {
    /**
     * Extract all image URLs from HTML content.
     *
     * @param string $content The HTML content to scan for images.
     * @return array An array of image URLs.
     */
    function extractImageUrls(string $content): array
    {
        preg_match_all('/<img[^>]+src="([^"]+)"/', $content, $matches);
        return $matches[1] ?? [];
    }

    if (!function_exists('removeVietnameseAccents')) {
        /**
         * Removes Vietnamese accents from a string.
         *
         * @param string $str
         * @return string
         */
        function removeVietnameseAccents($str): string
        {
            $accents = [
                'a' => ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ'],
                'd' => ['đ'],
                'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ'],
                'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị'],
                'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ'],
                'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự'],
                'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ']
            ];

            foreach ($accents as $key => $accentedChars) {
                foreach ($accentedChars as $accent) {
                    $str = str_replace($accent, $key, $str);
                }
            }

            // Replace uppercase accented characters as well
            return strtolower($str);
        }
    }

    if (!function_exists('generateCode')) {
        /**
         * Generate code
         *
         * @param string $code
         * @param int $id
         * @return string
         */
        function generateCode(string $code, int $id): string
        {
            return $code . str_pad($id, 4, '0', STR_PAD_LEFT);
        }
    }

    if (!function_exists('customPriceFormatCurrency')) {

        /**
         * Custom price format currency.
         *
         * @param $value
         * @param string $currency
         * @return string
         */
        function customPriceFormatCurrency($value, string $currency = 'đ'): string
        {
            if (empty($value)) {
                return '0' . $currency;
            }
            return number_format($value, 0, '.', ',') . $currency;
        }
    }
}
