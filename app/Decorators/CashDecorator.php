<?php

namespace App\Decorators;

use App\Helpers\MainHelper;
use App\Interfaces\CashInterface;
use App\Models\Cash;
use App\Repositories\CashRepository;
use Illuminate\Support\Facades\Cache;

class CashDecorator implements CashInterface
{

    protected $cash;

    public function __construct(CashRepository $cash)
    {
        $this->cash = $cash;
    }

    public function fetchByMonth(string  $month)
    {
        return $this->cash->fetchByMonth($month);
    }

    public function surrender($data, $cash_code): bool
    {
        $val = $this->cash->surrender($data, $cash_code);
        self::forgetCache();
        return $val;
    }

    public function lastCash(): string
    {
        return $this->cash->lastCash();
    }

    public function fetch(string $date): ?Cash
    {
        if (empty($date)) {
            $name = "cash_" . MainHelper::branchCode();
            $cash = Cache::rememberForever($name, function () {
                return $this->cash->fetch(now());
            });
        } else {
            $cash = $this->cash->fetch($date);
        }
        return $cash;
    }

    public function fetchChart()
    {
        $data = $this->cash->fetchChart();
        $months = [];
        $acu_mount = 0;
        $acum = [];
        $surr = [];

        $monthnames = [
            "1" => "Enero",
            "2" => "Febrero",
            "3" => "Marzo",
            "4" => "Abril",
            "5" => "Mayo",
            "6" => "Junio",
            "7" => "Julio",
            "8" => "Agosto",
            "9" => "Setiembre",
            "10" => "Octubre",
            "11" => "Noviembre",
            "12" => "Diciembre",
        ];
        foreach ($data as $value) {
            array_push($months, $monthnames[$value->month]);
            array_push($surr, $value->sum);
            $acu_mount += floatval($value->sum);
            array_push($acum, $acu_mount);
        }
        return [
            "months" => $months,
            "acum" => $acum,
            "surr" => $surr,
        ]; 
    }

    public function openCash(string $cash): Cash
    {
        self::forgetCache();
        $val = $this->cash->openCash($cash);
        return $val;
    }

    public function toggleCash(string $code): bool
    {
        $val = $this->cash->toggleCash($code);
        self::forgetCache();
        return $val;
    }

    public static function forgetCache()
    {
        return Cache::forget("cash_" . MainHelper::branchCode());
    }
}
