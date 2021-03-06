<?php

namespace App\Cache;

use Illuminate\Support\Facades\Cache;

class ConfigCache
{
    public static function manageDegreeCache(string $cycle_code, \Closure $callback)
    {
        $key = "degree_for_" . $cycle_code;
        return Cache::rememberForever($key, function () use ($callback, $cycle_code) {
            return $callback($cycle_code);
        });
    }

    public static function manageSectionCache(int $branch_code, \Closure $callback)
    {
        return Cache::rememberForever("sc_$branch_code", function () use ($callback) {
            return $callback();
        });
    }
}
