<?php

namespace App\Http\Controllers;

use App\Exports\IncomeExport;
use App\Http\Requests\IncomeRequest;
use App\Interfaces\IncomeInterface;
use Illuminate\Http\Request;

class IncomeController extends Controller
{

    private IncomeInterface $instance;

    public function __construct(IncomeInterface $instance)
    {
        $this->instance = $instance;
    }

    public function fetchByDates($from, $to)
    {
        return response()->json([
            "values" => $this->instance->fetchByDates($from, $to)
        ]);
    }

    public function canceleds()
    {
        return response()->json([
            "values" => $this->instance->canceleds()
        ]);
    }

    public function store(IncomeRequest $request)
    {
        try {
            $response = $this->instance->store($request->all());
            return response()->json([
                "message" => "Operación exitosa!",
                "income" => $response,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage(),
            ], 500);
        }
    }

    public function fetchNewIncomeNumber($type)
    {
        return response()->json([
            "number" => $this->instance->fetchNewIncomeNumber($type),
        ]);
    }

    public function canceled(Request $request, $code)
    {
        $this->instance->canceled($request->all(), $code);
        return response()->json("Anulado correctamente");
    }

    public function exportToExcel($from, $to)
    {
        return new IncomeExport($from, $to);
    }
}
