<?php

use Illuminate\Support\Str;

if (!function_exists('array_lowercase_keys')) {
    function array_lowercase_keys(array $array): array
    {
        return collect($array)
            ->map(fn($value, $key) => Str::lower($key))
            ->all();
    }
}

if (!function_exists('array_lowercase_values')) {
    function array_lowercase_values(array $array): array
    {
        return collect($array)
            ->map(fn($value, $key) => Str::lower($value))
            ->all();
    }
}
