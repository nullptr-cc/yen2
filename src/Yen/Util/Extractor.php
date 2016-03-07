<?php

namespace Yen\Util;

class Extractor
{
    final private function __construct()
    {
    }

    protected static function extract(array $data, $key, $default)
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        } else {
            return $default;
        };
    }

    public static function extractInt(array $data, $key, $default = 0)
    {
        $raw = static::extract($data, $key, $default);
        if (!is_numeric($raw)) {
            return $default;
        };

        return intval($raw);
    }

    public static function extractString(array $data, $key, $default = '')
    {
        $raw = static::extract($data, $key, $default);
        if (!is_string($raw)) {
            return $default;
        };

        return $raw;
    }

    public static function extractArray(array $data, $key, $default = [])
    {
        $raw = static::extract($data, $key, null);
        if ($raw === null) {
            return $default;
        };
        if (!is_array($raw)) {
            return [$raw];
        };

        return $raw;
    }
}
