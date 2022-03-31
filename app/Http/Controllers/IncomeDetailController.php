<?php

namespace App\Http\Controllers;

use App\Cache\IncomeCache;
use App\Cache\RegisterCache;
use App\Http\Requests\IncomeDetailRequest;
use App\Repositories\IncomeDetailRepository;

class IncomeDetailController extends Controller
{
    private IncomeDetailRepository $instance;

    function __construct(IncomeDetailRepository $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByIncome($code)
    {
        return response()->json([
            "values" => $this->instance->fetchByIncome($code)
        ]);
    }

    public function fetchStudentPayments($register_code)
    {
        return response()->json([
            "values" => $this->instance->fetchStudentPayments($register_code)
        ]);
    }

    public function showFromCache()
    {
        return response()->json([
            "values" => IncomeCache::fetchFromCache(),
            "register" => RegisterCache::fetchFromCache()
        ]);
    }

    public function storeInCache(IncomeDetailRequest $request)
    {
        $cachedValues = IncomeCache::fetchFromCache();
        $data = [
            "id" => rand(1000, 9999),
            "actiontype" => $request->input("actiontype"),
            "title" => $request->input("title"),
            "topay" => $request->input("topay"),
            "discount" => $request->input("discount"),
            "paid" => $request->input("paid"),
            "pending" => $request->input("pending"),
        ];
        // añadir el item al array
        array_push($cachedValues, $data);
        IncomeCache::put($cachedValues);
        return response()->json([
            "message" => "Ok! Se agrego un item!",
            "values" => $cachedValues
        ]);
    }

    public function removeItemFromCache($id)
    {
        $cachedValues = IncomeCache::fetchFromCache();
        // convertir a Collection 
        $collectedValues = collect($cachedValues);
        // buscar por id el item que ha de ser borrado
        $key = $collectedValues->search(function ($item) use ($id) {
            return $item["id"] == $id;
        });
        //  quitar el item
        $collectedValues->pull($key);
        // actualizar el cache
        IncomeCache::put($collectedValues->toArray());
        return response()->json("Un item ha sido borrado");
    }

    public function removeAllFromCache()
    {
        IncomeCache::forget();
        RegisterCache::forgetCache();
        return response()->json("Los registros han sido removidos");
    }

    public function printStudentPayments($register_code, $name)
    {
        /* $payments = $this->instance->fetchStudentPayments($register_code);
        $pdf = \PDF::loadView("reports.payments", compact("payments", "name"));
        $pdf->setPaper("A4", "landscape");
        return $pdf->download("adj.pdf"); */
    }
}
