<?php

namespace App\Models\Contracts;

use App\Enum\SettingStatus;

class SettingKey
{
    const BANNER_URL = [
        'key' => 'banner_url',
        'value' => null,
        'status' => SettingStatus::ENABLED,
    ];

    const CONTRACT_HEADER = [
        'key' => 'contract_header',
        'value' => null,
        'status' => SettingStatus::ENABLED,
    ];

    const CONTRACT_WATERMARK = [
        'key' => 'contract_watermark',
        'value' => null,
        'status' => SettingStatus::ENABLED,
    ];

    const KEY_JSON = [
        'key' => 'key_json',
        'value' => null,
        'status' => SettingStatus::ENABLED,
    ];

    public static function allKeys(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $keys = $class->getConstants();

            return array_values($keys);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function keysName(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $keys = $class->getConstants();
            foreach ($keys as $item) {
                if (is_array($item) && array_key_exists("key", $item)) {
                    $resultArray[] = $item["key"];
                }
            }

            return array_values($resultArray);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }
}
