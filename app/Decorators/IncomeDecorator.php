<?php

namespace App\Decorators;


use App\Interfaces\IncomeInterface;
use App\Models\Income;
use App\Repositories\IncomeDetailRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\RegisterRepository;
use App\Cache\IncomeCache;
use App\Cache\RegisterCache;
use Illuminate\Support\Facades\DB;


class IncomeDecorator implements IncomeInterface
{

    protected $income;

    public function __construct(IncomeRepository $income)
    {
        $this->income = $income;
    }

    public function fetchByDates(string $from, string $to)
    {
        return $this->income->fetchByDates($from, $to);
    }

    public function canceleds()
    {
        return $this->income->canceleds();
    }

    public function fetchNewIncomeNumber(string $type)
    {
        return $this->income->fetchNewIncomeNumber($type);
    }

    public function store($data): Income
    {
        try {
            DB::beginTransaction();

            $details = IncomeCache::fetchFromCache();
            $register = RegisterCache::fetchFromCache();

            // 1.- guardar registro, si es que hay
            $responseRegister = (new RegisterRepository())->storeOrUpdate($register);
            // si ha registrado la matricula
            if ($responseRegister !== false) {
                $data["customer_identifier"] = $responseRegister;
            }
      
            // 2.- guardar cabecera
            $income = $this->income->store($data);

            // 3.- guardar detalle
            if ($details !== null) {
                $incomeDetail = (new IncomeDetailRepository());
                foreach ($details as $value) {
                    $incomeDetail->store($value, $income->code);
                }
            }

            //cache
            IncomeCache::forget();
            CashDecorator::forgetCache();
            if (!empty($data["section_code"])) {
                RegisterCache::forgetPays($data["section_code"]);
                RegisterCache::forgetCache();
            }
            DB::commit();
            return $income;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new \Exception("Error Procesando el Ingreso: " . $ex->getMessage());
        }
    }

    public function canceled(array $data, $code): bool
    {
        $can = $this->income->canceled($data, $code);
        CashDecorator::forgetCache();
        RegisterCache::forgetPays($data["section_code"]);
        return $can;
    }
}
