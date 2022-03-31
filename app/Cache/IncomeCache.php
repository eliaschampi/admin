<?php

namespace App\Cache;

use App\Helpers\MainHelper;
use Illuminate\Support\Facades\Cache;

class IncomeCache
{

    public static function fetchFromCache()
    {
        return Cache::get("income_detail_" . MainHelper::branchCode(), []);
    }

    public static function forget(): void
    {
        Cache::forget("income_detail_" . MainHelper::branchCode());
    }

    public static function put(array $data): void
    {
        Cache::put("income_detail_" . MainHelper::branchCode(), $data, 3600);
    }

    public static function set(array $data): void
    {
        Cache::set("income_detail_" . MainHelper::branchCode(), $data, 3600);
    }
}
