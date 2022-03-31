<?php

namespace App\Cache;

use App\Helpers\MainHelper;
use Illuminate\Support\Facades\Cache;

class RegisterCache
{
    //ojo set no put
    public static function setCache($data)
    {
        Cache::put("register_" . MainHelper::branchCode(), $data, 3600);
    }

    public static function hasOnCache()
    {
        return Cache::has("register_" . MainHelper::branchCode(), false);
    }

    public static function fetchFromCache()
    {
        return Cache::get("register_" . MainHelper::branchCode(), false);
    }

    public static function forgetCache()
    {
        Cache::forget("register_" .  MainHelper::branchCode());
    }

    public static function forgetPays($section_code)
    {
        Cache::tags("pay")->forget($section_code);
    }
}
